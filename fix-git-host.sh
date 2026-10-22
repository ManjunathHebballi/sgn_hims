#!/bin/bash
# Fix git dubious ownership on host machine

echo "Fixing git dubious ownership..."

# Add safe directory for this repository
git config --global --add safe.directory /root/manjunath-space/hims

# Or trust all directories (less secure but simpler)
# git config --global --add safe.directory '*'

echo "Fixed! You can now run git commands."

# Test
git status
