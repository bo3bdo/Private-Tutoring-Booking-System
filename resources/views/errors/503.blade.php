@extends('errors::minimal')

@section('title', __('common.Service Unavailable'))
@section('code', '503')
@section('message', __('common.Service Unavailable'))
@section('description', __('common.The service is temporarily unavailable. Please try again later.'))
