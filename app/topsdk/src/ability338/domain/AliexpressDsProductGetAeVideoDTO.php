<?php
namespace Topsdk\Topapi\Ability338\Domain;

class AliexpressDsProductGetAeVideoDTO {

    /**
        Seller's master account ID
     **/
    private $ali_member_id;

    /**
        Video ID
     **/
    private $media_id;

    /**
        Video status
     **/
    private $media_status;

    /**
        Type of video
     **/
    private $media_type;

    /**
        The URL of the video cover image
     **/
    private $poster_url;


    public function getAliMemberId() : int{
        return $this->ali_member_id;
    }

    public function setAliMemberId(int $aliMemberId){
        $this->ali_member_id = $aliMemberId;
    }

    public function getMediaId() : int{
        return $this->media_id;
    }

    public function setMediaId(int $mediaId){
        $this->media_id = $mediaId;
    }

    public function getMediaStatus() : string{
        return $this->media_status;
    }

    public function setMediaStatus(string $mediaStatus){
        $this->media_status = $mediaStatus;
    }

    public function getMediaType() : string{
        return $this->media_type;
    }

    public function setMediaType(string $mediaType){
        $this->media_type = $mediaType;
    }

    public function getPosterUrl() : string{
        return $this->poster_url;
    }

    public function setPosterUrl(string $posterUrl){
        $this->poster_url = $posterUrl;
    }


}

