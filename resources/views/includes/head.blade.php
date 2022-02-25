<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Chameleon Admin is a modern Bootstrap 4 webapp &amp; admin dashboard html template with a large number of components, elegant design, clean and organized code.">
<meta name="keywords" content="admin template, Chameleon admin template, dashboard template, gradient admin template, responsive admin template, webapp, eCommerce dashboard, analytic dashboard">
<meta name="author" content="ThemeSelect">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>Fixed Layout - Chameleon Admin - Modern Bootstrap 4 WebApp & Dashboard HTML Template + UI Kit</title>
<link rel="apple-touch-icon" href="{{ asset('/theme/app-assets/images/ico/apple-icon-120.png') }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ asset('/theme/app-assets/images/ico/boatek.png') }}">
<link href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i%7CComfortaa:300,400,700" rel="stylesheet">

{{--jquery for globally access ajax calls--}}
<!-- BEGIN: Custom Js-->
<script src="{{asset('/theme/app-assets/vendors/js/tables/jquery-1.12.3.js')}}" type="text/javascript"></script>
<!-- END: Custom JS-->
<script>
    $(function ()
    {
        siteUrl = '<?php echo URL::to('/'); ?>/';
        {{--s3Url = '<?php echo AdminCommonHelper::$images_CDN; ?>';--}}
        {{--itemSlug = '<?php echo AdminCommonHelper::$s3_images_slug; ?>';--}}

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });
</script>
