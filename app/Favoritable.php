<?php


namespace App;


trait Favoritable
{


    protected static function bootFavoritable()
    {

        static::deleting(function ($model)
        {
            $model->favorites->each->delete();
        });

    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        if (!$this->favorites()->where(['user_id' => auth()->id()])->exists()) {
            return $this->favorites()->create(['user_id' => auth()->id()]);
        }

    }

    public function unFavorite()
    {
         $attributes = ['user_id' => auth()->id()];
        return $this->favorites()->where($attributes)->get()->each(function ($favorite){
            $favorite->delete();
        });

    }
}
