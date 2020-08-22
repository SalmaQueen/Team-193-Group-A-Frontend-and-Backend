@extends('layouts.index')

@section("content")
    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <div class="masthead-subheading">Welcome to FarePlan</div>
            <div class="masthead-heading text-uppercase">We take Ma3 Industry Cashless</div>
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
            </div>
            <form id="contactForm" name="sentMessage" novalidate="novalidate">
                <div class="row align-items-stretch mb-5">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input class="form-control" id="name" type="text" placeholder="Your Name *" required="required" data-validation-required-message="Please enter your name." />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="form-group">
                            <input class="form-control" id="email" type="email" placeholder="Your Email *" required="required" data-validation-required-message="Please enter your email address." />
                            <p class="help-block text-danger"></p>
                        </div>
                        <div class="form-group mb-md-0">
                            <input class="form-control" id="phone" type="tel" placeholder="Your Phone *" required="required" data-validation-required-message="Please enter your phone number." />
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-textarea mb-md-0">
                            <textarea class="form-control" id="message" placeholder="Your Message *" required="required" data-validation-required-message="Please enter a message."></textarea>
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <div id="success"></div>
                    <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Send Message</button>
                </div>
            </form>
        </div>
    </section>
@stop
