@extends('layouts.app')

@section('content')
<div id="reports" class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Reports</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul class="nav nav-tabs">
                      <li class="nav-item" @click="selectTab('debt')">
                        <a class="nav-link" :class="{ active: tab === 'debt' }" href="#">Debt</a>
                      </li>
                      <li class="nav-item" @click="selectTab('payment')">
                        <a class="nav-link" :class="{ active: tab === 'payment' }" href="#">Payment</a>
                      </li>
                      <li class="nav-item" @click="selectTab('total')">
                        <a class="nav-link" :class="{ active: tab === 'total' }" href="#">Total</a>
                      </li>
                    </ul>

                    <div v-if="tab === 'debt'">
                        <div class="input-group" style="margin-bottom: 14px">
                            <input v-model="debtDate" type="date" class="form-control">
                        </div>
                        <ul class="list-group list-group-flush" style="height: 300px;overflow-y: auto;">
                            <li class="list-group-item" v-for="item in debts">
                                <a @click="loadDebtReport(item)" href="#">debt: <% item.title %>, value: <% item.value %>, date: <% item.action_date %></a>
                            </li>
                        </ul>
                        balance: <% debtBalance %>
                        <ul class="list-group list-group-flush" style="height: 300px;overflow-y: auto;">
                            <li class="list-group-item" v-for="item in debtReport">
                                payment: <% item.title %>,  paid: <% item.value %>, date:  <% item.action_date %>
                            </li>
                        </ul>

                    </div>
                    <div v-if="tab === 'payment'">
                        <ul class="list-group list-group-flush" style="height: 300px;overflow-y: auto;">
                            <li class="list-group-item" v-for="item in payments">
                                <a href="#" @click="loadPaymentReport(item)">payment: <% item.title %>, value: <% item.value %>, date: <% item.action_date %></a>
                            </li>
                        </ul>

                        <ul class="list-group list-group-flush" style="height: 300px;overflow-y: auto;">
                            <li class="list-group-item" v-for="item in paymentReport">
                                debt: <% item.title %>,  paid: <% item.value %>
                            </li>
                        </ul>
                    </div>
                    <div v-if="tab === 'total'">
                        <div class="input-group" style="margin-bottom: 14px">
                            <input @change="loadDTotalReport" v-model="totalDate" type="date" class="form-control">
                        </div>

                        <ul class="list-group list-group-flush" style="height: 300px;overflow-y: auto;">
                                debts: <% total.debts %>, payments: <% total.payments %>, balance: <% total.balance %>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        var payment = new Vue({
            el: '#reports',
            data: {
                tab: "debt",
                debts: [],
                debtDate: "",
                debtReport: [],
                debtBalance: 0,
                payments: [],
                paymentReport: [],
                totalDate: "",
                total: {
                    debts: 0,
                    payments: 0,
                    balance: 0,
                },
            },
            methods: {
                selectTab: function(tab) {
                    this.tab = tab;
                },
                loadPaymentReport: function(item) {
                    var self = this;
                    axios.post('{{ route('report-debt') }}', {id: item.id})
                    .then(function(response) {
                        self.paymentReport = response.data.report;
                    });
                },
                loadDebtReport: function(item) {
                    var self = this;
                    axios.post('{{ route('report-debt') }}', {id: item.id, date: self.debtDate})
                    .then(function(response) {
                        self.debtReport = response.data.report;
                        self.debtBalance = response.data.balance;
                    });
                },
                loadDTotalReport: function(item) {
                    var self = this;
                    axios.post('{{ route('report-total') }}', {date: self.totalDate})
                    .then(function(response) {
                        self.total = response.data;
                    });
                },
            },
            delimiters: ["<%", "%>"],
            mounted: function() {
                var self = this;
                axios.get('{{ route('report-lists') }}')
                .then(function(response) {
                    self.debts = response.data.debts;
                    self.payments = response.data.payments;
                });
            }
        });
    </script>
@endsection
