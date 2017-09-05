@extends('layouts.base')

@section('caption', 'Settings')

@section('title', 'Settings')

@section('lyric', 'lorem ipsum')

@section('content')
    <!-- will be used to show any messages -->
    @if(session()->has('message_success'))
        <div class="alert alert-success">
            <strong>Well done!</strong> {{ session()->get('message_success') }}
        </div>
    @elseif(session()->has('message_danger'))
        <div class="alert alert-danger">
            <strong>Danger!</strong> {{ session()->get('message_danger') }}
        </div>
    @endif

    <!-- /. ROW  -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {{ Form::open(array('url' => 'settings')) }}
                            <div class="panel-group" id="accordion">
                                <div class="panel panel-default" id="panel1">
                                    <div class="panel-heading" style="background-color: #f6f6f6;padding: 10px 0px 20px 20px">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-target="#collapseOne" class="collapsed">
                                                Global settings #1
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse in">
                                        <div class="panel-body" style="background-color: #f6f6f6">
                                            <div class="col-lg-6">
                                                <div class="form-group input-row">
                                                    {{ Form::label('pagination_size', 'Pagination size') }}
                                                    {{ Form::text('pagination_size', config('crm_settings.pagination_size'), array('class' => 'form-control')) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="panel panel-default" id="panel2">
                                    <div class="panel-heading" style="background-color: #f6f6f6;padding: 10px 0px 20px 20px">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-target="#collapseTwo" class="collapsed">
                                                Client settings #2
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse">
                                        <div class="panel-body" style="background-color: #f6f6f6">
                                            <div class="col-lg-6">
                                                <div class="form-group input-row">
                                                    {{ Form::label('priority_size', 'Priority size') }}
                                                    {{ Form::text('priority_size', config('crm_settings.priority_size'), array('class' => 'form-control')) }}
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group input-row">
                                                    {{ Form::label('currency', 'Currency type') }}
                                                    {{ Form::select('currency', ['PLN' => 'PLN', 'EUR' => 'EUR', 'USD' => 'USD'], config('crm_settings.currency'), ['class' => 'form-control']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 validate_form">
                            {{ Form::submit('Submit Button', array('class' => 'btn btn-primary')) }}
                        </div>
                    {{ Form::close() }}

                    <!-- /.row (nested) -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <script>
            $(document).ready(function() {
                //create formValidator object
                //there are a lot of configuration options that need to be passed,
                //but this makes it extremely flexibility and doesn't make any assumptions
                var validator = new formValidator({
                    //this function adds an error message to a form field
                    addError: function(field, message) {
                        //get existing error message field
                        var error_message_field = $('.error_message',field.parent('.input-row'));

                        //if the error message field doesn't exist yet, add it
                        if(!error_message_field.length) {
                            error_message_field = $('<span/>').addClass('error_message');
                            field.parent('.input-row').append(error_message_field);
                        }

                        error_message_field.text(message).show(200);
                        field.addClass('error');
                    },
                    //this removes an error from a form field
                    removeError: function(field) {
                        $('.error_message',field.parent('.input-row')).text('').hide();
                        field.removeClass('error');
                    },
                    //this is a final callback after failing to validate one or more fields
                    //it can be used to display a summary message, scroll to the first error, etc.
                    onErrors: function(errors, event) {
                        //errors is an array of objects, each containing a 'field' and 'message' parameter
                    },
                    //this defines the actual validation rules
                    rules: {
                        'pagination_size': {
                            'field': $('input[name=pagination_size]'),
                            'validate': function(field, event) {
                                //if the validation is fired from a blur event,
                                //don't throw any errors if it is empty

                                if(!field.val()) {
                                    throw "A pagination size is required."

                                };

                                var pagination_pattern = /[0-9]$/i;
                                if(!pagination_pattern.test(field.val())) {
                                    throw "A pagination must be integer.";
                                }

                            }
                        },
                        'priority_size': {
                            'field': $('input[name=priority_size]'),
                            'validate': function(field, event) {
                                //if the validation is fired from a blur event,
                                //don't throw any errors if it is empty

                                if(!field.val()) {
                                    throw "A priority size is required."

                                };

                                var priority_pattern = /[0-9]$/i;
                                if(!priority_pattern.test(field.val())) {
                                    throw "A priority must be integer.";
                                }

                            }
                        }
                    }
                });

                //now, we attach events

                //this does validation every time a field loses focus
                $('form').on('blur','input,select',function() {
                    validator.validateField($(this).attr('name'),'blur');
                });

                //this clears errors every time a field gains focus
                $('form').on('focus','input,select',function() {
                    validator.clearError($(this).attr('name'));
                });

                //this is for the validate links
                $('.validate_section').click(function() {
                    var fields = [];
                    $('input,select',$(this).closest('.section')).each(function() {
                        fields.push($(this).attr('name'));
                    });

                    if(validator.validateFields(fields,'submit')) {
                        alert('success');
                    }
                    return false;
                });
                $('.validate_form').click(function() {
                    if(!validator.validateFields('submit')) {
                        return false;
                    }
                    return true;
                });

                //this is for the clear links
                $('.clear_section').click(function() {
                    var fields = [];
                    $('input,select',$(this).closest('.section')).each(function() {
                        fields.push($(this).attr('name'));
                    });

                    validator.clearErrors(fields);
                    return false;
                });
            });
        </script>
@endsection
