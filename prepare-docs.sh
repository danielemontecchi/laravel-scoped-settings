#!/bin/bash

# Automatically inject version warning banners and deploy documentation with mike
set -e

# Detect current Git tag (e.g., v1.0.0)
VERSION=$(git describe --tags --abbrev=0 2>/dev/null || echo "latest")
VERSION_CLEANED=${VERSION#v}

echo "📌 Detected version: $VERSION_CLEANED"

# Step 1: Inject version-warning.md if not already included
echo "⏳ Injecting version warnings where needed..."

INCLUDE='--8<-- "partials/version-warning.md"'
VERSIONED_DIR="./docs"

for dir in "$VERSIONED_DIR"/*; do
  if [[ -d "$dir" && "$dir" != *"latest"* && "$(basename "$dir")" != .* ]]; then
    echo "📁 Processing directory: $dir"
    for file in "$dir"/*.md; do
      if ! grep -Fxq "$INCLUDE" "$file"; then
        echo "➕ Adding banner to: $file"
        tmpfile=$(mktemp)
        echo "$INCLUDE" > "$tmpfile"
        cat "$file" >> "$tmpfile"
        mv "$tmpfile" "$file"
      else
        echo "✓ Banner already present in: $file"
      fi
    done
  fi
done

echo "✅ Banner injection complete."

# Step 2: Deploy with mike
echo "🚀 Deploying docs with mike..."
mike deploy --push --update-aliases "$VERSION_CLEANED" latest
mike set-default latest

echo "✅ Deployment complete for version: $VERSION_CLEANED"
