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
git tag v1.0.0 && git push origin v1.0.0
git push origin :refs/tags/v1.0.0 && git tag -d v1.0.0
```
