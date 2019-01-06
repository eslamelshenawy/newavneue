@extends('admin.index')
@section('content')
    <div id="compose" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 90%">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.email') }}</h4>
                </div>
                <div class="modal-body text-center">
                    <form action="{{url(adminPath().'/mail_post')}}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <select class="select2 form-control" style="width: 100%" name="lead_id" data-placeholder="{{ __('admin.lead') }}">
                                <option></option>
                                @foreach(@\App\Lead::get() as $lead)
                                    <option value="{{ $lead->email }}">{{ $lead->first_name . ' ' . $lead->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="subject" placeholder="{{ __('admin.subject') }}">
                        </div>
                        <br/>
                        <br/>
                        <div class="col-md-12">
                            <textarea name="message" id="editor"></textarea>
                        </div>
                        <br/>
                        <br/>
                </div>
                <div class="modal-footer">
                    <div class="col-md-12">
                        <br/>
                        <button type="button" class="btn btn-default btn-flat"
                                data-dismiss="modal">{{ trans('admin.close') }}</button>
                        <button type="submit" class="btn btn-success btn-flat">{{ trans('admin.send') }}</button>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div id="mail" class="modal fade" role="dialog">
        <div class="modal-dialog" style="width: 90%">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">{{ trans('admin.email') }}</h4>
                </div>
                <div class="modal-body text-center" id="mailBody">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-flat"
                            data-dismiss="modal">{{ trans('admin.close') }}</button>
                </div>
            </div>

        </div>
    </div>
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ $title }}</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body">
            <button data-toggle="modal" data-target="#compose" type="button"
                    class="btn btn-danger btn-flat">{{ __('admin.compose') }}</button>
            <table class="table datatable">
                <thead>
                <tr>
                    <th>{{ __('admin.subject') }}</th>
                    <th>{{ __('admin.from') }}</th>
                    <th>{{ __('admin.date') }}</th>
                    <th>{{ __('admin.show') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($messages as $message)
                    <tr id="tr{{ $message->msgno }}" @if(!$message->seen) style="background: rgba(193, 66, 66, 0.37)" @endif>
                        <td>{{ $message->subject }}</td>
                        <td>{{ $message->from }}</td>
                        <td>{{ $message->date }}</td>
                        <td>
                            <button type="button" class="getMail btn btn-primary btn-flat"
                                    msgno="{{ $message->msgno }}">{{ __('admin.show') }}</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).on('click', '.getMail', function () {
            var id = $(this).attr('msgno');
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url(adminPath().'/get_mail')}}/" + id,
                method: 'post',
                dataType: 'html',
                data: {id: id, _token: _token},
                beforeSend: function () {
                    $('#mailBody').html('<i class="fa fa-circle-o-notch fa-spin fa-2x"></i>');
                    $('#mail').modal('show');
                },
                success: function (data) {
                    $('#mailBody').html(data);
                    $('#tr'+id).css('background','#fff')
                },
                error: function () {
                    alert('{{ __('admin.error') }}')
                }
            })
        })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ckeditor/4.7.3/adapters/jquery.js"></script>
    <script>
        $('#editor').ckeditor();
    </script>
@endsection