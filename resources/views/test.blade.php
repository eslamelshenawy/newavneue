<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Places Searchbox</title>
</head>
<body>
<form href="{{ url('test/test') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="file" name="bible[]" multiple>
    abbrev<input type="text" name="abbrev">
    book<input type="text" name="book">
    <input type="submit">
</form>
</body>
</html>
