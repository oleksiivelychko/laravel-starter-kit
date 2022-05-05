@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __('Service Unavailable'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
