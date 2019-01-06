<div class="well col-md-12">
    <div class="col-md-2 text-center">
        <img height="50" width="50" style="border-radius: 50px; border: 2px solid #caa42d" src="{{ url('uploads/'.@\App\User::find($note->user_id)->image) }}">
        <br/>
        <br/>
        <span style="color: gray">{{ $note->created_at }}</span>
    </div>
    <div class="col-md-10">
        <p>
            {{ $note->note }}
        </p>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#newComment').html('{{ $note->note }}')
    })
</script>