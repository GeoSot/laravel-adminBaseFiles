<fieldset>
            <legend>Export all translations</legend>
            <form class="form-inline form-publish-all" method="POST" action="{{action('\Barryvdh\TranslationManager\Controller@postPublish', '*') }}" data-remote="true" role="form"
                  data-confirm="Are you sure you want to publish all translations group? This will overwrite existing language files.">
                    @csrf()
                <button type="submit" class="btn btn-primary" data-disable-with="Publishing..">Publish all</button>
            </form>
        </fieldset>