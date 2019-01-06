@extends('admin.index')
@section('content')
    <input type="text" id="msg">
    <button id="btn" type="button">SUBMIT</button>
@endsection
@section('js')
    <script>
        $('#btn').on('click', function () {
            var msg = $('#msg').val();
            var socket = io.connect('http://localhost:3000');
            socket.emit('notify', {
                msg: msg
            });
        })
    </script>
@endsection
