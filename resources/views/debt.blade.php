@extends('layouts.app')

@section('content')
<div id="debt" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add new debt</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div v-cloak v-if='success' class="alert alert-success" role="alert">
                        Debt saved
                    </div>

                    <div v-cloak v-if="message" class="alert alert-danger" role="alert">
                        <% message %>
                    </div>

                    <div class="input-group" style="margin-bottom: 14px">
                        <input v-model="title" type="text" class="form-control" placeholder="title">
                    </div>

                    <div class="input-group" style="margin-bottom: 14px">
                        <input v-model="date" type="date" class="form-control">
                    </div>

                    <div class="input-group" style="margin-bottom: 14px">
                        <input v-model="value" type="number" step="0.01" class="form-control" placeholder="value">
                    </div>
                    <button type="button" class="btn btn-primary" @click="save">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var debt = new Vue({
            el: '#debt',
            data: {
                title: "",
                value: null,
                date: "",
                success: false,
                message: "",
            },
            methods: {
                save: function() {
                    var self = this;
                    var data = {
                        title: this.title,
                        value: this.value,
                        date: this.date,
                    };

                    axios.post('{{ route('store-debt') }}', data)
                    .then(function(response) {
                        self.success = response.data.success;
                        self.message = response.data.message;

                        if (response.data.success) {
                            self.title = "";
                            self.value = "";
                        }
                    }).catch(function (error) {
                        self.success = false;

                        var errors = [];
                        for (var i in error.response.data.errors) {
                            errors.push(error.response.data.errors[i]);
                        }
                        self.message = errors.join(" ");
                    });
                },
            },
            delimiters: ["<%", "%>"],
        });
    </script>
@endsection
