### Merge branches.

```
git fetch origin
git checkout develop-branch
git pull
```

_(Optional)_ Recreate local branch from remote:
```
git branch --delete --force develop-branch
git checkout -b develop-branch /origin/develop-branch
```

Merge `hotfix-branch` into `develop-branch`:
```
git merge origin/hotfix-branch --no-ff
```
💡 _no-fast-forward (optional)_ to preserve the history of commits into `develop-branch`.

If there are conflicts, fix them and:
```
git merge --continue
```
...or cancel the merging action:
```
git merge --abort
```

After that - push changes:
```
git push
```

_(Optional)_ Delete remote branch:
```
git push origin --delete origin/hotfix-branch
```

### Rebase branches.

Merge `hotfix-branch` onto `develop-branch`:
```
git checkout hotfix-branch
git pull --rebase origin develop-branch
git push --force
```

If there are conflicts, fix them and:
```
git rebase --continue
```
...or cancel the rebasing action:
```
git rebase --abort
```
