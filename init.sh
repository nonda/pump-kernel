#!/bin/bash

git config project.name 'pump-kernel'
git config commit.template .github/.gitmessage
cd .git/hooks && ln -s ../../.github/prepare-commit-msg

echo "init done."
