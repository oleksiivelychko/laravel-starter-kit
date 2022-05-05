@extends('errors::minimal')

@section('title', __('Unauthorized'))
@section('code', '401')
@section('message', __('Unauthorized'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
