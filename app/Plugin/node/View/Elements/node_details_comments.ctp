<?php 
    $i = 1;
    $count = count($Layout['node']['Comment']);

    if ($count > 0):
?>
    <h2><?php echo __d('comment', 'Comments'); ?></h2>
    <div id="comments-list">
        <div class="comments-pagination paginator top">
            <?php $this->Paginator->options( array( 'url'=> $this->passedArgs)); ?>
            <?php echo $this->Paginator->prev(__d('comment', '«'), null, null, array('class' => 'disabled')); ?>
            <?php echo $this->Paginator->numbers( array('separator' => ' ')); ?>
            <?php echo $this->Paginator->next(__d('comment', '»'), null, null, array('class' => 'disabled')); ?>
        </div>
        
        <?php foreach ($Layout['node']['Comment'] as $comment): ?>
            <?php $this->Layout->hook('comment_alter', $comment, array('alter' => true, 'collectReturn' => false)); ?>
            <?php $comment_time_zone = $this->Layout->loggedIn() ? $this->Session->read('Auth.User.timezone') : Configure::read('Variable.date_default_timezone');  ?>
                <div id="<?php echo "comment-{$comment['Comment']['id']}"; ?>" class="comment <?php echo $i%2 ? 'even': 'odd'; ?> <?php echo $i==1 ? 'comment-first' : ''; ?> <?php echo $i == $count ? 'comment-last' : ''; ?>">
                    <div class="attribution">
                        <div class="submited">
                            <p class="commenter-name">
                                <?php
                                    if (
                                        ( isset($comment['User']['avatar']) && !empty($comment['User']['avatar'])) || 
                                        ( !isset($comment['User']['avatar']) && Configure::read('Variable.user_default_avatar') != '' )   
                                
                                    ): ?>
                                    <div class="avatar">
                                        <?php $avatar =  (isset($comment['User']['avatar']) && !empty($comment['User']['avatar'])) ? $comment['User']['avatar'] : Configure::read('Variable.user_default_avatar') ; ?>
                                        <?php echo $this->Html->image($avatar); ?>
                                    </div>
                                <?php endif; ?>
                                <?php $userURL = !empty($comment['Comment']['homepage']) ? $comment['Comment']['homepage'] : 'javascript: return false;'; ?>
                                <?php $userURL = empty($userURL) && isset($comment['User']['username']) ? $this->Html->url("/user/profile/{$comment['User']['username']}") : $userURL; ?>
                                <a href="<?php echo $userURL; ?>" class="username" rel="nofollow">
                                    <?php echo isset($comment['User']['username']) ? $comment['User']['username'] : $comment['Comment']['name']; ?>
                                </a>
                            </p>
                            <p class="comment-time"><span><?php echo __d('comment', 'said on %s', $this->Time->format(__t('M d, Y H:i'), $comment['Comment']['created'], null, $comment_time_zone)); ?></span></p>
                            <p class="comment-permalink"><?php echo $this->Html->link(__d('comment', 'Permalink'), "/d/{$Layout['node']['Node']['slug']}#comment-{$comment['Comment']['id']}", array('id' => "comment-{$comment['Comment']['id']}", 'class' => 'permalink')); ?></p>
                        </div>
                    </div>
                    <div class="comment-body">
                        <div class="comment-text">
                            <div class="comment-actions">
                                <?php $this->Layout->hook('commentActions', $this, array('alter' => true, 'collectReturn' => false)); ?>
                                <?php if ($Layout['node']['Node']['comment'] == 2): ?>
                                    <a href="" onClick="quoteComment(<?php echo $comment['Comment']['id']; ?>); return false;" class="quote"><?php echo __d('comment', 'Quote'); ?></a>
                                <?php endif; ?>
                            </div>
                            <?php if ($Layout['node']['NodeType']['comments_subject_field']): ?>
                                <h3><?php echo $this->Html->link($comment['Comment']['subject'], "/d/{$Layout['node']['Node']['slug']}#comment-{$comment['Comment']['id']}", array('class' => 'permalink')); ?></h3>
                            <?php endif; ?>
                            <p><?php echo $comment['Comment']['body']; ?></p>
                            <p id="raw-comment-<?php echo $comment['Comment']['id']; ?>" style="display:none;"><?php echo $comment['Comment']['raw_body']; ?></p>
                        </div>
                    </div>
                </div>
            <?php $i++; ?>
        <?php endforeach; ?>
        
        <div class="comments-pagination paginator bottom">
            <?php $this->Paginator->options( array( 'url'=> $this->passedArgs)); ?>
            <?php echo $this->Paginator->prev(__d('comment', '«'), null, null, array('class' => 'disabled')); ?>
            <?php echo $this->Paginator->numbers( array('separator' => ' ')); ?>
            <?php echo $this->Paginator->next(__d('comment', '»'), null, null, array('class' => 'disabled')); ?>
        </div>
    </div>
<?php endif; ?>