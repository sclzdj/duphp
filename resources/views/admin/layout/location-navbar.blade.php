<div class="bg-gray-lighter" id="location-navbar">
    <ol class="breadcrumb">
        <li><i class="fa fa-map-marker"></i></li>
        @foreach(\App\Servers\NavigationServer::location() as $systemNode)
            <li><a class="link-effect" href="@if($systemNode['action']!==''){{action($systemNode['action'])}} @else javascript:void(0); @endif">{{$systemNode['name']}}</a></li>
        @endforeach
    </ol>
</div>
