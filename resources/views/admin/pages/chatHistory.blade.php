@extends('admin.main')

@section('content')	

	<chat-history-component :chat_histories="{{$chatHistories}}"></chat-history-component>	
	
@endsection