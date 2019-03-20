
    <form action="{{ action('\Barryvdh\TranslationManager\Controller@postAdd', array($group))}}" method="POST" role="form">
               @csrf()
        <div class="form-group">
                <label>Add new keys to this group</label>
                <textarea class="form-control" rows="3" name="keys" placeholder="Add 1 key per line, without the group prefix"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" value="Add keys" class="btn btn-primary">
            </div>
        </form>
    <hr>
    <h4>Total: {{$numTranslations}}, changed: {{$numChanged}}</h4>
    <table class="table">
            <thead>
            <tr>
                <th width="15%">Key</th>
                @foreach ($locales as $locale)
                    <th>{{$locale}}</th>
                @endforeach
                @if ($deleteEnabled)
                    <th>&nbsp;</th>
                @endif
            </tr>
            </thead>
            <tbody>

           @foreach ($translations as $key => $translation)
               <tr id="<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>">
                    <td><?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?></td>
                   @foreach ($locales as $locale)
                       <?php $t = isset($translation[$locale]) ? $translation[$locale] : null ?>

                       <td>
                            <a href="#edit"
                               class="editable status-<?php echo $t ? $t->status : 0 ?> locale-{{$locale}}"
                               data-locale="<?php echo $locale ?>" data-name="<?php echo $locale . "|" . htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>"
                               id="username" data-type="textarea" data-pk="<?php echo $t ? $t->id : 0 ?>"
                               data-url="<?php echo $editUrl ?>"
                               data-title="Enter translation"><?php echo $t ? htmlentities($t->value, ENT_QUOTES, 'UTF-8', false) : '' ?></a>
                        </td>
                   @endforeach
                   @if ($deleteEnabled)
                       <td>
                            <a href="<?php echo action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) ?>"
                               class="delete-key"
                               data-confirm="Are you sure you want to delete the translations for '<?php echo htmlentities($key, ENT_QUOTES, 'UTF-8', false) ?>?"><span
                                        class="glyphicon glyphicon-trash"></span></a>
                        </td>
                   @endif
                </tr>
           @endforeach
            </tbody>
        </table>