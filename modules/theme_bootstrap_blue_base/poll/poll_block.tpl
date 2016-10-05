<div class="row poll-block-wrap">
    <div class="col-sm-10 col-sm-offset-1">
        <div class="poll-block">
            <div class="row">
                <div class="col-md-4">
                    <div class="h2 title">{$question.title|escape}</div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <form method="post" action="{page_path module='poll' function='vote_for_answer'}" class="form" role="form">
                        <input type="hidden" name="votequestion" value="{$question.id}" />
                        {CSRF_token}

                        <div class="row">
                            <div class="col-md-12 col-sm-6 col-sm-offset-4 col-xs-9 col-xs-offset-2 col-md-offset-0">
                                <ul class="list-unstyled">
                                    {foreach from=$answers item="answer"}
                                        <li class="custom-form-control">
                                            <input type="radio" name="voteanswer" id="voteanswer{$answer.id}" value="{$answer.id}" />
                                            <label class="radio" for="voteanswer{$answer.id}">{$answer.title|escape}</label>
                                        </li>
                                    {/foreach}
                                </ul>
                            </div>
                        </div>

                        <div class="row text-center">
                            <div class="col-md-5">
                                <input type="submit" class="btn h5 btn-long btn-orange" value="[[Vote:raw]]" />
                            </div>
                            <div class="col-md-7">
                                <br/>
                                <a class="viewResults middle" href='{page_path module='poll' function='vote_results'}'>[[View poll results]]</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<div class="space-20"></div>
