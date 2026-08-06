<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('layouts.styles')
<?php $isvertical = false; ?>
@if($isvertical == true)
<body data-sidebar="dark">
@else
<body data-topbar="dark" data-layout="horizontal">
@endif
<div id="layout-wrapper">
@include('layouts.header')
@if($isvertical == true)
@include('layouts.leftnav')
@else
@include('layouts.topnav')
@endif
<div class="main-content">
<div class="page-content">
<div class="container-fluid" style="max-width: 100%;">
@yield('PageContent')
</div>
</div>
</div>

</div>
<div class="rightbar-overlay"></div>
@include('layouts.scripts')
</body>
</html>
