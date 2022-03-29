@extends('layouts.app')
@section('content')
    <script type="application/javascript">
        var toDoList = {!! json_encode($toDoList) !!};
    </script>
    <to-dos-component></to-dos-component>
@endsection