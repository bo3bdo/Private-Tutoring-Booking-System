@extends('errors::minimal')

@section('title', __('common.Too Many Requests'))
@section('code', '429')
@section('message', __('common.Too Many Requests'))
@section('description', __('common.You have made too many requests. Please wait a moment and try again.'))
