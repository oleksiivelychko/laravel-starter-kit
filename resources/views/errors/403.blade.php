@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
