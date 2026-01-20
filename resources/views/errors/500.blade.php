@extends('errors::minimal')

@section('title', __('common.Server Error'))
@section('code', '500')
@section('message', __('common.Server Error'))
@section('description', __('common.We are experiencing some technical difficulties. Please try again later.'))
