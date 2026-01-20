@extends('errors::minimal')

@section('title', __('common.Unauthorized'))
@section('code', '401')
@section('message', __('common.Unauthorized'))
@section('description', __('common.Please log in to access this resource.'))
