@extends('layouts.app')
    @section('content')
        <div class="container">
            <Chats :user="{{ auth()->user() }}"></Chats>
        </div>    
     @endsection
