<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title> @yield('title') </title>
    @include('includes.head')

    {{--    local style for each page start--}}
    @include('includes.theme-style')
    {{--    local style for each page end--}}

    {{--    local style for each page start--}}
    @yield('local-style')
    {{--    local style for each page end--}}
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu 2-columns   fixed-navbar" data-open="click" data-menu="vertical-menu" data-color="bg-gradient-x-purple-blue" data-col="2-columns">

<!-- BEGIN: Header-->
<!-- fixed-top-->
@include('includes.top-nav-bar')
<!-- END: Header-->


<!-- BEGIN: Main Menu-->
@include('includes.side-nav-bar')
<!-- END: Main Menu-->

<!-- BEGIN: Content-->
    @yield('body')
<!-- END: Content-->


<!-- BEGIN: Footer-->
@include('includes.footer')
<!-- END: Footer-->





<!-- BEGIN: Theme JS-->
 @include('includes.theme-js')
<!-- END: Theme JS-->


{{--    local style for each page start--}}
@yield('local-script')
{{--    local style for each page end--}}

</body>
<!-- END: Body-->

</html>
