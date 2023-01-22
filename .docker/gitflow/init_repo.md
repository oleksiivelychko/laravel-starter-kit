### Make remote repository as initial.

check out to a temporary branch:
```
git checkout --orphan temp_branch
```

add all existing files:
```
git add -A
```

commit the changes:
```
git commit -am "initial commit"
```

delete the old branch:
```
git branch -D main
```

rename the temporary branch to main:
```
git branch -m main
```

force update to repository:
```
git push -f origin main
```
