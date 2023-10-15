This apptakes a CSV file as input, like the example below, and generates Laravel Model files based on the data.

```
"indirect-emissions-owned,electricity",meeting-rooms
```

To run the app after installing the Laravel project, you need to execute the 'app:files-make' command with the file path as an argument, like this:
```
php artisan app:files-make ../models.csv
```