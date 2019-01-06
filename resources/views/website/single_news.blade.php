@extends('website.index')
@section('content')
<!-- News Details Start -->
<section id="news-section-1" class="news-section-details property-details padding_top">
	<div class="container">

		<div class="row heading_space">

			<div class="col-md-8">

				<div class="row">

					<div class="news-1-box clearfix">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<img src="{{ url('uploads/'.$single_news->image) }}" alt="{{ $single_news->{app()->getLocale().'_title'} }}" class="img-responsive"/>
						</div>

						<div class="col-md-12 col-sm-12 col-xs-12 top30">

							<div class="news-details bottom10">
								<span><i class="icon-icons228"></i> {{ date('M d Y',strtotime($single_news->created_at)) }} </span>
							</div>

							<h3 style="color: #caa42d">{{ $single_news->{app()->getLocale().'_title'} }}</h3>



							<p class=" top30 bottom30">{{ $single_news->{app()->getLocale().'_description'} }}</p>

						</div>
					</div>

				</div>



				<div class="row heading_space">

					<div class="news-2-tag">
						<div class="col-md-5 col-sm-5 col-xs-12 top15">
							<h4>{{ __('admin.tags') }} : </h4>
							<p class="p-font-15">@if($single_news->event == 1) {{ __('admin.event') }} @endif @if($single_news->news == 1) ','. {{ __('admin.news') }} @endif @if($single_news->launch == 1) ',' {{ __('admin.launch') }} @endif</p>
						</div>

						<div class="col-md-7 col-sm-7 col-xs-12 text-right">
							<div class="social-icons">
								<div class="sharethis-inline-share-buttons"></div>

							</div>
						</div>
					</div>

				</div>


			</div>
			<aside class="col-md-4 col-xs-12 ">
				<div class="property-query-area clearfix">
					@include('website/search')
				</div>
				@include('website.featured_projects',['featured',$featured])
			</aside>

		</div>

	</div>
</section>
<!-- News Details End -->
@endsection