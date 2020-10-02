@extends('layouts.index')

@section("content")
    <!-- Masthead-->

    <header class="masthead">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                @if(Session::has('mail_sent'))
                    <p class="bg-danger"></p>
                    <div class="alert alert-success">
                        <strong>Success!</strong> {{session('mail_sent')}}
                    </div>
                @endif
                @if(Session::has('mail_sent_fail'))
                    <p class="bg-danger"></p>
                    <div class="alert alert-danger">
                        <strong>FAILED!</strong> {{session('mail_sent_fail')}}
                    </div>
                @endif
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="container">
            <div class="masthead-subheading">Welcome to FarePlan</div>
            <div class="masthead-heading text-uppercase">We manage your ma3</div>

            <a class="btn btn-primary btn-md text-uppercase js-scroll-trigger" href="#contact">Join FarePlan</a>
        </div>
    </header>
    <!-- About-->
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">About</h2>
                <h3 class="section-subheading text-muted">Who we are. <br>One matatu, one person, one transaction at a time.</h3>
            </div>
            <ul class="timeline">
                <li>
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="{{asset('home/assets/img/about/1.jpg')}}" alt="" /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>OUR PLAN</h4>
                        </div>
                        <div class="timeline-body"><p class="text-muted">Our plan is to automate the matatu sector payment procedures</p></div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="{{asset("home/assets/img/about/2.jpg")}}" alt="" /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>OUR VISION</h4>
                        </div>
                        <div class="timeline-body"><p class="text-muted">Our vision is to have automated payments across the entire matatu sector</p></div>
                    </div>
                </li>
                <li>
                    <div class="timeline-image"><img class="rounded-circle img-fluid" src="{{asset("home/assets/img/about/3.jpg")}}" alt="" /></div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4>OUR MISSION</h4>
                        </div>
                        <div class="timeline-body"><p class="text-muted">Love, Care and Collaboration throgh digital pay for stress free safe drive</p></div>
                    </div>
                </li>
                <li class="timeline-inverted">
                    <a class="nav-link js-scroll-trigger" href="#contact">
                        <div class="timeline-image">
                            <h4>Be Part<br />Of Our<br />Story!</h4>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </section>
    <!-- Contact-->
    <section class="page-section" id="contact">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Contact Us</h2>
                <h3 class="section-subheading text-light">Looking forward to hearing from you.</h3>
                <div id="pay">
                    <script>
                        function myFunction() {
                            document.getElementById("pay").innerHTML =
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                '<p class="text-success">Loading..</p>'+
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n' +
                                '<span class="spinner-grow spinner-grow-sm bg-success"></span>\n';
                        };
                    </script>
                </div>

            </div>

            {!! Form::open(['method'=>'POST', 'action'=> 'EmailsController@store']) !!}
            <div class="row align-items-stretch mb-5">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Your Name *','required'=>true, 'id'=>"name"])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('email', 'Email:') !!}
                        {!! Form::email('email', null, ['class'=>'form-control','placeholder'=>'Your Email *','required'=>true])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('phone', 'Phone:') !!}
                        {!! Form::tel('phone', null, ['class'=>'form-control','placeholder'=>'Your Phone *','required'=>true,'maxlength'=>10])!!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('message_body', 'Message:') !!}
                        {!! Form::textarea('message_body', null, ['class'=>'form-control','placeholder'=>'Your Message *','required'=>true])!!}
                    </div>
                </div>
            </div>
            <div class="text-center">
                <div class="form-group">
                    {!! Form::submit('Send Message', ['class'=>'btn btn-primary btn-xl text-uppercase','onclick'=>'myFunction()']) !!}
                </div>
            </div>
            {!! Form::close() !!}

        </div>
    </section>
@stop
