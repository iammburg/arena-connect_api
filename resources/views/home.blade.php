@extends('dashboard-layouts.app')
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Greeting Message Section -->
        <div class="row mb-3">
            <div class="col-12 col-md-8">
                <!-- Enhanced Text Styling -->
                <h2 class="text-greeting">
                    HAI ADMIN SELAMAT DATANG<br><br>LANGKAH MUDAH MENGELOLA<br><br>APLIKASI BOOKING LAPANGAN
                </h2>
            </div>
            <div class="col-12 col-md-4">
                <!-- Container for two phone images -->
                <div class="d-flex justify-content-between">
                    <img src="{{ asset('assets/images/ARENA CONNECT APPS.png') }}" alt="Phone Image 1" class="img-fluid" style="max-width: 45%; height: auto;">
                    <img src="{{ asset('assets/images/ARENA CONNECT APPS.png') }}" alt="Phone Image 2" class="img-fluid" style="max-width: 45%; height: auto;">
                </div>
            </div>
        </div>

        <!-- Customer Table Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="active-member">
                            <div class="table-responsive">
                                <table class="table table-xs mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nama User</th>
                                            <th>Nama Lapangan</th>
                                            <th>Booking Start</th>
                                            <th>Booking End</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Pengguna 1</td>
                                            <td>Lapangan Futsal</td>
                                            <td><span>08:00</span></td>
                                            <td><span>11:00</span></td>
                                            <td>Terverifikasi</td>
                                        </tr>
                                        <!-- Repeat other rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Stats Section -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="social-graph-wrapper widget-facebook">
                        <span class="s-icon"><i class="fa-brands fa-facebook"></i></span>
                    </div>
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">89k</h4>
                                <p class="m-0">Friends</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">119k</h4>
                                <p class="m-0">Followers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="social-graph-wrapper widget-linkedin">
                        <span class="s-icon"><i class="fa-brands fa-linkedin"></i></span>
                    </div>
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">89k</h4>
                                <p class="m-0">Friends</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">119k</h4>
                                <p class="m-0">Followers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="social-graph-wrapper widget-googleplus">
                        <span class="s-icon"><i class="fa-brands fa-google-plus-g"></i></span>
                    </div>
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">89k</h4>
                                <p class="m-0">Friends</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">119k</h4>
                                <p class="m-0">Followers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 mb-3">
                <div class="card">
                    <div class="social-graph-wrapper widget-twitter">
                        <span class="s-icon"><i class="fa-brands fa-twitter"></i></span>
                    </div>
                    <div class="row">
                        <div class="col-6 border-right">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">89k</h4>
                                <p class="m-0">Friends</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="pt-3 pb-3 text-center">
                                <h4 class="m-1">119k</h4>
                                <p class="m-0">Followers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection