#!/bin/bash

set -euo pipefail

echo "🔁 Starting full documentation rebuild (one version per major)..."

# 🧹 Step 1: Delete all previously deployed versions
echo "🧹 Deleting existing documentation..."
mike delete --all || echo "No previous versions to delete"

# 📦 Step 2: Get latest tag per major
declare -A latest_per_major

for tag in $(git tag -l "v*" | sort -V); do
  major=$(echo "$tag" | cut -d. -f1) # v1, v2, ...
  latest_per_major["$major"]=$tag
done

# 🚀 Step 3: Deploy documentation for each major version
for major in "${!latest_per_major[@]}"; do
  tag="${latest_per_major[$major]}"
  echo "📦 Deploying tag $tag as version $major"

  git checkout "$tag"
  mike deploy "$major" --push
done

# 🏁 Step 4: Checkout main branch
echo "🔄 Switching back to main..."
git checkout main

# 🌟 Step 5: Set latest alias to most recent major version
LATEST_MAJOR=$(git tag -l "v*" | sort -V | tail -n1 | cut -d. -f1)
echo "⭐ Setting default version to: $LATEST_MAJOR"
mike set-default "$LATEST_MAJOR" --push

echo "✅ All major documentation versions deployed and published!"
