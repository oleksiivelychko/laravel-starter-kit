### Common Git operations.

Change repository URL:
```
git remote set-url origin https://github.com/user/repository.git
git remote add origin git@github.com/user/repository.git
```

Make a commit "in the past":
```
git commit --date="10 day ago" -m "10 days ago changes" 
```

Reset repository to remote state:
```
git fetch origin
git reset --hard origin/main
```

Undo (one) commit(-s):
```
git reset HEAD~1
git push -f
```

Prettify git log output:
```
git log --pretty=oneline --abbrev-commit
```

Update the author metadata (user/email) for commit(-s):
```
git rebase -r <commit_revision_number_before_merge> --exec 'git commit --amend --no-edit --reset-author'
git push -f
```

Tagging:
```
tag=1.0.0 && git tag v${tag} && git push origin v${tag}
tag=1.0.0 && git push origin :refs/tags/v${tag} && git tag -d v${tag}
```

Rename branch locally (first) and remotely (will be removed):
```
git branch -m old_branch new_branch 
git push origin :old_branch    
git push --set-upstream origin new_branch
```
