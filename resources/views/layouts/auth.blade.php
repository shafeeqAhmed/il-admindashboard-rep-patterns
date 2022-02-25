<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <title> @yield('title') </title>
    @include('includes.head')
    @include('includes.theme-style')
</head>
<!-- END: Head-->
<!-- BEGIN: Body-->
    @yield('body')
<!-- END: Body-->

    @include('includes.theme-js')
</html>
