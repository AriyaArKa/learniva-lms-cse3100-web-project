@extends('frontend.master')
@section('title', 'Privacy Policy')
@section('home')

    <!--================================
             START BREADCRUMB AREA
    =================================-->
    <section class="breadcrumb-area section-padding img-bg-2">
        <div class="overlay"></div>
        <div class="container">
            <div class="breadcrumb-content d-flex flex-wrap align-items-center justify-content-between">
                <div class="section-heading">
                    <h2 class="section__title text-white">Privacy Policy</h2>
                </div>
                <ul
                    class="generic-list-item generic-list-item-white generic-list-item-arrow d-flex flex-wrap align-items-center">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li>Privacy Policy</li>
                </ul>
            </div>
        </div>
    </section>
    <!--================================
        END BREADCRUMB AREA
    =================================-->

    <!--======================================
            START PRIVACY CONTENT
    ======================================-->
    <section class="about-area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="privacy-content">
                        <h3 class="widget-title">1. Introduction</h3>
                        <p class="pb-3">
                            Your privacy is important to us. This Privacy Policy explains how we collect, use, disclose, and
                            safeguard your information when you visit our Learning Management System platform. Please read
                            this privacy policy carefully. If you do not agree with the terms of this privacy policy, please
                            do not access the site.
                        </p>

                        <h3 class="widget-title">2. Information We Collect</h3>
                        <p class="pb-3">
                            We collect information about you in a variety of ways. The information we may collect on the
                            platform includes:
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">Personal Data</h5>
                        <p class="pb-3">
                            Personally identifiable information, such as your name, email address, phone number, and
                            demographic information that you voluntarily give to us when you register with the platform or
                            when you choose to participate in various activities related to the platform.
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">Payment Information</h5>
                        <p class="pb-3">
                            We may collect data necessary to process your payment if you make purchases, such as your
                            payment instrument number (credit card number), and the security code associated with your
                            payment instrument. All payment data is stored by our payment processor and you should review
                            its privacy policies.
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">Usage Data</h5>
                        <p class="pb-3">
                            Information our servers automatically collect when you access the platform, such as your IP
                            address, browser type, operating system, access times, and the pages you have viewed directly
                            before and after accessing the platform.
                        </p>

                        <h3 class="widget-title">3. How We Use Your Information</h3>
                        <p class="pb-3">
                            We use the information we collect or receive:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-check mr-2 text-success"></i>To create and manage your account</li>
                            <li><i class="la la-check mr-2 text-success"></i>To process your transactions and send you
                                related information</li>
                            <li><i class="la la-check mr-2 text-success"></i>To send you administrative information, such as
                                updates, security alerts, and support messages</li>
                            <li><i class="la la-check mr-2 text-success"></i>To respond to your comments, questions, and
                                provide customer service</li>
                            <li><i class="la la-check mr-2 text-success"></i>To send you marketing and promotional
                                communications</li>
                            <li><i class="la la-check mr-2 text-success"></i>To improve our platform and develop new
                                features</li>
                            <li><i class="la la-check mr-2 text-success"></i>To monitor and analyze usage and trends to
                                improve your experience</li>
                            <li><i class="la la-check mr-2 text-success"></i>To detect, prevent, and address technical
                                issues and protect against fraud</li>
                        </ul>

                        <h3 class="widget-title">4. Disclosure of Your Information</h3>
                        <p class="pb-3">
                            We may share information we have collected about you in certain situations. Your information may
                            be disclosed as follows:
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">By Law or to Protect Rights</h5>
                        <p class="pb-3">
                            If we believe the release of information about you is necessary to respond to legal process, to
                            investigate or remedy potential violations of our policies, or to protect the rights, property,
                            and safety of others, we may share your information as permitted or required by any applicable
                            law, rule, or regulation.
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">Third-Party Service Providers</h5>
                        <p class="pb-3">
                            We may share your information with third parties that perform services for us or on our behalf,
                            including payment processing, data analysis, email delivery, hosting services, customer service,
                            and marketing assistance.
                        </p>

                        <h5 class="fs-16 font-weight-semi-bold pb-2">Business Transfers</h5>
                        <p class="pb-3">
                            We may share or transfer your information in connection with, or during negotiations of, any
                            merger, sale of company assets, financing, or acquisition of all or a portion of our business to
                            another company.
                        </p>

                        <h3 class="widget-title">5. Cookies and Tracking Technologies</h3>
                        <p class="pb-3">
                            We may use cookies, web beacons, tracking pixels, and other tracking technologies on the
                            platform to help customize the platform and improve your experience. When you access the
                            platform, your personal information is not collected through the use of tracking technology.
                            Most browsers are set to accept cookies by default. You can remove or reject cookies, but be
                            aware that such action could affect the availability and functionality of the platform.
                        </p>

                        <h3 class="widget-title">6. Security of Your Information</h3>
                        <p class="pb-3">
                            We use administrative, technical, and physical security measures to help protect your personal
                            information. While we have taken reasonable steps to secure the personal information you provide
                            to us, please be aware that despite our efforts, no security measures are perfect or
                            impenetrable, and no method of data transmission can be guaranteed against any interception or
                            other type of misuse.
                        </p>

                        <h3 class="widget-title">7. Data Retention</h3>
                        <p class="pb-3">
                            We will retain your personal information only for as long as is necessary for the purposes set
                            out in this Privacy Policy. We will retain and use your information to the extent necessary to
                            comply with our legal obligations, resolve disputes, and enforce our policies.
                        </p>

                        <h3 class="widget-title">8. Your Privacy Rights</h3>
                        <p class="pb-3">
                            Depending on your location, you may have the following rights regarding your personal
                            information:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-check mr-2 text-success"></i>The right to access – You have the right to
                                request copies of your personal data</li>
                            <li><i class="la la-check mr-2 text-success"></i>The right to rectification – You have the right
                                to request that we correct any information you believe is inaccurate</li>
                            <li><i class="la la-check mr-2 text-success"></i>The right to erasure – You have the right to
                                request that we erase your personal data, under certain conditions</li>
                            <li><i class="la la-check mr-2 text-success"></i>The right to restrict processing – You have the
                                right to request that we restrict the processing of your personal data, under certain
                                conditions</li>
                            <li><i class="la la-check mr-2 text-success"></i>The right to data portability – You have the
                                right to request that we transfer the data that we have collected to another organization,
                                or directly to you, under certain conditions</li>
                        </ul>

                        <h3 class="widget-title">9. Children's Privacy</h3>
                        <p class="pb-3">
                            Our platform is not intended for children under 13 years of age. We do not knowingly collect
                            personal information from children under 13. If you become aware that a child has provided us
                            with personal information, please contact us. If we become aware that we have collected personal
                            information from children without verification of parental consent, we take steps to remove that
                            information from our servers.
                        </p>

                        <h3 class="widget-title">10. Third-Party Websites</h3>
                        <p class="pb-3">
                            The platform may contain links to third-party websites and applications of interest. Once you
                            have used these links to leave the platform, any information you provide to these third parties
                            is not covered by this Privacy Policy, and we cannot guarantee the safety and privacy of your
                            information.
                        </p>

                        <h3 class="widget-title">11. Changes to This Privacy Policy</h3>
                        <p class="pb-3">
                            We may update this Privacy Policy from time to time in order to reflect, for example, changes to
                            our practices or for other operational, legal, or regulatory reasons. We will notify you of any
                            changes by posting the new Privacy Policy on this page and updating the "Last Updated" date.
                        </p>

                        <h3 class="widget-title">12. Contact Us</h3>
                        <p class="pb-3">
                            If you have questions or comments about this Privacy Policy, please contact us at:
                        </p>
                        <ul class="generic-list-item pb-3">
                            <li><i class="la la-envelope mr-2"></i>Email: privacy@lms.com</li>
                            <li><i class="la la-phone mr-2"></i>Phone:
                                {{ App\Models\SiteSetting::find(1)->phone ?? 'Contact Support' }}</li>
                            <li><i class="la la-map-marker mr-2"></i>Address:
                                {{ App\Models\SiteSetting::find(1)->address ?? 'See Contact Page' }}</li>
                        </ul>

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
            END PRIVACY CONTENT
    ======================================-->

@endsection
