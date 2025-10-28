@extends('frontend.master')
@section('title', 'Terms & Conditions')
@section('home')

    <!--================================
             START BREADCRUMB AREA
    =================================-->
    <section class="breadcrumb-area section-padding img-bg-2">
        <div class="overlay"></div>
        <div class="container">
            <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="section-heading">
                    <h2 class="section__title text-white">Terms & Conditions</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Terms & Conditions</li>
                </ul>
            </div>
        </div>
    </section>
    <!--================================
        END BREADCRUMB AREA
    =================================-->

    <!--======================================
            START TERMS CONTENT
    ======================================-->
    <section class="about-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="terms-content">
                        <h3 class="widget-title">1. Introduction</h3>
                        <p class="pb-3">
                            Welcome to our Learning Management System. By accessing and using this platform, you accept and
                            agree to be bound by the terms and provision of this agreement. If you do not agree to abide by
                            the above, please do not use this service.
                        </p>

                        <h3 class="widget-title">2. Use License</h3>
                        <p class="pb-3">
                            Permission is granted to temporarily access the materials (information or software) on our LMS
                            for personal, non-commercial transitory viewing only. This is the grant of a license, not a
                            transfer of title, and under this license you may not:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-check mr-2 text-success"></i>Modify or copy the materials</li>
                            <li><i class="la la-check mr-2 text-success"></i>Use the materials for any commercial purpose or
                                for any public display</li>
                            <li><i class="la la-check mr-2 text-success"></i>Attempt to decompile or reverse engineer any
                                software contained on our platform</li>
                            <li><i class="la la-check mr-2 text-success"></i>Remove any copyright or other proprietary
                                notations from the materials</li>
                            <li><i class="la la-check mr-2 text-success"></i>Transfer the materials to another person or
                                "mirror" the materials on any other server</li>
                        </ul>

                        <h3 class="widget-title">3. User Accounts</h3>
                        <p class="pb-3">
                            When you create an account with us, you must provide us information that is accurate, complete,
                            and current at all times. Failure to do so constitutes a breach of the Terms, which may result
                            in immediate termination of your account on our Service.
                        </p>
                        <p class="pb-3">
                            You are responsible for safeguarding the password that you use to access the Service and for any
                            activities or actions under your password. You agree not to disclose your password to any third
                            party.
                        </p>

                        <h3 class="widget-title">4. Course Enrollment and Access</h3>
                        <p class="pb-3">
                            When you enroll in a course, you get lifetime access to that course's content, including all
                            future updates. However, we reserve the right to:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-check mr-2 text-success"></i>Modify, suspend, or discontinue any course at
                                any time</li>
                            <li><i class="la la-check mr-2 text-success"></i>Revoke access to courses if terms are violated
                            </li>
                            <li><i class="la la-check mr-2 text-success"></i>Limit the number of devices on which you can
                                access the content</li>
                        </ul>

                        <h3 class="widget-title">5. Payment and Refunds</h3>
                        <p class="pb-3">
                            All course prices are subject to change without notice. We accept various payment methods as
                            indicated on our payment page. Our refund policy allows for refunds within 30 days of purchase
                            if you are not satisfied with the course, subject to our refund policy terms.
                        </p>

                        <h3 class="widget-title">6. Intellectual Property</h3>
                        <p class="pb-3">
                            The Service and its original content, features, and functionality are and will remain the
                            exclusive property of our LMS and its licensors. The Service is protected by copyright,
                            trademark, and other laws. You may not copy, modify, create derivative works of, publicly
                            display, publicly perform, republish, or transmit any of the material obtained through our
                            Service.
                        </p>

                        <h3 class="widget-title">7. User Conduct</h3>
                        <p class="pb-3">
                            You agree not to use the Service:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-times mr-2 text-danger"></i>In any way that violates any applicable national
                                or international law or regulation</li>
                            <li><i class="la la-times mr-2 text-danger"></i>To transmit, or procure the sending of, any
                                advertising or promotional material</li>
                            <li><i class="la la-times mr-2 text-danger"></i>To impersonate or attempt to impersonate the
                                Company, an employee, another user, or any other person or entity</li>
                            <li><i class="la la-times mr-2 text-danger"></i>To engage in any other conduct that restricts or
                                inhibits anyone's use or enjoyment of the Service</li>
                        </ul>

                        <h3 class="widget-title">8. Disclaimer</h3>
                        <p class="pb-3">
                            The materials on our platform are provided on an 'as is' basis. We make no warranties, expressed
                            or implied, and hereby disclaim and negate all other warranties including, without limitation,
                            implied warranties or conditions of merchantability, fitness for a particular purpose, or
                            non-infringement of intellectual property or other violation of rights.
                        </p>

                        <h3 class="widget-title">9. Limitations</h3>
                        <p class="pb-3">
                            In no event shall our LMS or its suppliers be liable for any damages (including, without
                            limitation, damages for loss of data or profit, or due to business interruption) arising out of
                            the use or inability to use the materials on our platform.
                        </p>

                        <h3 class="widget-title">10. Revisions and Errata</h3>
                        <p class="pb-3">
                            The materials appearing on our platform could include technical, typographical, or photographic
                            errors. We do not warrant that any of the materials on our platform are accurate, complete, or
                            current. We may make changes to the materials contained on our platform at any time without
                            notice.
                        </p>

                        <h3 class="widget-title">11. Governing Law</h3>
                        <p class="pb-3">
                            These terms and conditions are governed by and construed in accordance with the laws and you
                            irrevocably submit to the exclusive jurisdiction of the courts in that location.
                        </p>

                        <h3 class="widget-title">12. Changes to Terms</h3>
                        <p class="pb-3">
                            We reserve the right, at our sole discretion, to modify or replace these Terms at any time. If a
                            revision is material, we will try to provide at least 30 days' notice prior to any new terms
                            taking effect. What constitutes a material change will be determined at our sole discretion.
                        </p>

                        <h3 class="widget-title">13. Contact Information</h3>
                        <p class="pb-3">
                            If you have any questions about these Terms & Conditions, please contact us through our contact
                            page or email us at support@lms.com.
                        </p>

                        <div class="section-block mt-4 mb-4"></div>

                        <p class="fs-14 text-black-50">
                            <strong>Last Updated:</strong> {{ date('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--======================================
            END TERMS CONTENT
    ======================================-->

@endsection
