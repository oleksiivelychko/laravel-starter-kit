@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Page Expired'))
@section('url', route('home', app()->getLocale()))
@section('url_text', __('auth.go-home'))
