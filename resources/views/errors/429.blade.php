@extends('errors::minimal')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', __('Too Many Requests'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
