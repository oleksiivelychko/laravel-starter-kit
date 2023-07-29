### Make remote repository as initial.

Check out to a temporary branch:
```
git checkout --orphan temp_branch
```

Add all existing files:
```
git add -A
```

Commit the changes:
```
git commit -am "initial commit"
```

Delete the old branch:
```
git branch -D main
```

Rename the temporary branch to main:
```
git branch -m main
```

Force update to repository:
```
git push -f origin main
```
