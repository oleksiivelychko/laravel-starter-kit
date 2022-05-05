@extends('errors::minimal')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
