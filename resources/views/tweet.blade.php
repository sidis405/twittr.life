<div class="panel panel-default">
  <div class="panel-body">
    <div class="media">
      <div class="media-left">
        <a href="https://www.twitter.com/{{$tweet->twitter_user}}" target="_blank">
          <img class="media-object img-circle" style="width: 50px" src="{{ $tweet->twitter_user_image }}" alt="{{ $tweet->twitter_user }}">
        </a>
      </div>
      <div class="media-body">
        <h4 class="media-heading">
          <a href="https://www.twitter.com/{{$tweet->twitter_user}}" target="_blank" >{{$tweet->twitter_user}}</a> {{ '@' . $tweet->twitter_user_name }}
          <small class="pull-right">{{ $tweet->tweet_created_at->diffForHumans() }}</small>
        </h4>
        <p>
          {!! $tweet->text !!}
        </p>

        @if($tweet->quote)
          @include('tweet', ['tweet' => $tweet->quote])
        @endif

        @if(count($tweet->tweet_media))
          <p>
            @foreach($tweet->tweet_media as $media)
            <a href="{{ $media }}" target="_blank">
              <img class="img-responsive" src="{{ $media }}"/>
            </a>
            @endforeach
          </p>
        @endif
        <small class="media-footer">
          <span class="pull-left"><a href="{{ $tweet->tweet_url }}" target="_blank">View original <i class="fa fa-twitter"></i></a></span>
          <span class="pull-right">imported {{ $tweet->created_at->format('d/m/Y H:i') }}</span>
        </small>
      </div>
    </div>
  </div>
</div>
