#!/usr/bin/env bash
# exit on error
set -o errexit

# Clean any existing packages
rm -rf .venv
rm -rf __pycache__

# Create fresh virtual environment
python -m venv .venv
source .venv/bin/activate

# Install dependencies
pip install --no-cache-dir -r requirements.txt 