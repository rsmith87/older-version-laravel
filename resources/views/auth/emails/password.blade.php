
@extends('beautymail::templates.widgets')

@section('content')

	@include('beautymail::templates.widgets.articleStart')
<h1>Legaleeze</h1>
<p>Your boss has made the best choice in legal apps for you to use with your firm!  The link below will take you to finish your account setup so you can be rockin'
  and rollin' with the best online legal software available!</p>

<p>Thank you for your continued support,<br />
  The Legaleeze Team</p>
	@include('beautymail::templates.widgets.articleEnd')


	@include('beautymail::templates.widgets.newfeatureStart')

	<p>Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a></p>

	@include('beautymail::templates.widgets.newfeatureEnd')

@stop