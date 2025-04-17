#!/bin/bash

set -e

echo "🔵 Using mike from: $(which mike)"
echo "📦 Mike version: $(mike --version)"

# Checkout gh-pages branch e pulizia
echo "🧹 Cleaning up gh-pages..."
git fetch origin gh-pages
git switch gh-pages
git pull origin gh-pages
rm -rf *
rm -rf .idea/

echo "✅ Cleaned gh-pages branch."

# Ricava i tag ordinati e prendi solo l'ultima patch per ogni major
echo "🚀 Collecting latest tag for each major..."
TAGS=$(git tag --sort=-v:refname | grep -E '^v[0-9]+\.[0-9]+\.[0-9]+$')

declare -A majors
for tag in $TAGS; do
  major=$(echo "$tag" | cut -d. -f1) # v1, v2, ...
  if [ -z "${majors[$major]}" ]; then
    majors[$major]=$tag
  fi
done

# Trova la major più alta per alias 'latest'
latest_tag=""
for major in "${!majors[@]}"; do
  tag="${majors[$major]}"
  if [ -z "$latest_tag" ] || [[ "$tag" > "$latest_tag" ]]; then
    latest_tag="$tag"
  fi
done

# Deploy delle versioni major
echo "📚 Starting documentation deploy (one version per major)..."
for major in "${!majors[@]}"; do
  tag="${majors[$major]}"
  alias="${tag%%.*}" # v1, v2, ...
  git checkout "tags/$tag"
  if [ "$tag" == "$latest_tag" ]; then
    mike deploy --update-aliases "$alias" latest "$tag"
    echo "✅ Deployed $tag as $alias and latest"
  else
    mike deploy --update-aliases "$alias" "$tag"
    echo "📦 Deployed $tag as $alias"
  fi
done

# Crea redirect da index.html alla versione latest
echo "🔁 Creating index.html redirect to /latest/"
cat <<EOF > index.html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="refresh" content="0; url=latest/" />
    <script>window.location.href = 'latest/'</script>
    <title>Redirecting...</title>
  </head>
  <body>
    <p>Redirecting to <a href="latest/">latest version</a>.</p>
  </body>
</html>
EOF

# Commit e push
git add .
git commit -m "Deployed documentation to $latest_tag with major-only versions and latest alias"
git push origin gh-pages

# Ritorna a main
git switch main
