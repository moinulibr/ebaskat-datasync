<?php
namespace App\Traits;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

/**
 * This Trait use for only File handling.
 * Image/File store / update / delete
 */
trait FileStorage
{
    protected $destination;
    protected $imageWidth;
    protected $imageHeight;
    protected $file;

    protected $id;
    protected $imageNameFromDb;
    


    /**for private property only */
        private $extension;
        private $originalNameWithExtension;
        private $imageFileNameToStore;
        private $imageResize;
    /**for private property only */

    
    /*
    |---------------------------------------------------------------------------------------------
    |   Store Image
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory
        $this->storeImage();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function storeImage() 
        {
            // Get filename with extension
            $originalNameWithExtension = strtolower($this->file->getClientOriginalName());
            // Get file path
            $filenameWithSpace = pathinfo($originalNameWithExtension, PATHINFO_FILENAME);
           
            // Get the original image extension
            $this->extension = strtolower($this->file->getClientOriginalExtension());

            // Create unique file name
            $this->imageFileNameToStore =   str_replace(' ', '-', $filenameWithSpace).'-'.time()."-".rand(100000,999999).'.'.$this->extension;
            //$this->imageFileNameToStore =   $filenameWithSpace.'-'.time()."-".rand(100000,999999).'.'.$this->extension;

            // Refer image to method resizeImage
            $this->resizeAndStoreImage();
            return $this->imageFileNameToStore;
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End image insert
    |---------------------------------------------------------------------------------------------
    */
       

    //its not using
    /* public function insertImage()
    {   
        if ($this->file)
        {
            $this->extension    = strtolower($this->file->getClientOriginalExtension());
            $this->originalName = strtolower($this->file->getClientOriginalName());
            if ($this->extension != "jpg" && $this->extension != "jpeg" && $this->extension != "png" && $this->extension != "gif")
            {
                $this->extension = '';
            }
            else
            {
                $this->imageFileNameToStore = $this->id.".".$this->extension;
                $this->resizeAndStoreImage();
                return $this->extension;
            }
        }
        return "";
    } */
   //its not using




    /*
    |---------------------------------------------------------------------------------------------
    | Update Image
    | return image extension/imageName after inserting image by some property 
    | $this->destination, $this->imageWidth,$this->imageHeight,$this->file$this->imageNameFromDb,//$this->id
        $this->destination  = ;  //its mandatory
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory
        $this->imageNameFromDb = ;  //its mandatory
        $this->updateImage();
    | by call this $this->updateImage() method....
    |---------------------------------------------------------------------------------------------
    */
        public function updateImage()
        {
            if ($this->file)
            {
                if($this->imageNameFromDb)
                {
                    $this->imageDelete();
                }

                // Get filename with extension
                $originalNameWithExtension = strtolower($this->file->getClientOriginalName());
                // Get file path
                $filenameWithSpace = pathinfo($originalNameWithExtension, PATHINFO_FILENAME);

                // Get the original image extension
                $this->extension = strtolower($this->file->getClientOriginalExtension());

                // Create unique file name
                $this->imageFileNameToStore =   str_replace(' ', '-', $filenameWithSpace).'-'.time()."-".rand(100000,999999).'.'.$this->extension;
                //$this->imageFileNameToStore =   $filenameWithSpace.'-'.time()."-".rand(100000,999999).'.'.$this->extension;

                // Refer image to method resizeImage
                $this->resizeAndStoreImage();
                return $this->imageFileNameToStore;
            }
            return "";
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End image update
    |---------------------------------------------------------------------------------------------
    */




    /*
    |--------------------------------------------------------------------------------------------------
    | Resize and Store Image
    | Image resize and upload  (this method use for store and update image) 
    |-------------------------------------------------------------------------------------------------- 
    */
        public function resizeAndStoreImage() 
        {
            // Resize image
            $this->imageResize = Image::make($this->file)->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
            $constraint->aspectRatio();
            })->encode($this->extension);

            // Put image to storage
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}", $this->imageResize);

            if($save) {
                return $this->imageFileNameToStore;
            }
            return false;
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End image update
    |---------------------------------------------------------------------------------------------
    */




    /*
    |---------------------------------------------------------------------------------------------
    | Image delete
    | $this->destination , $this->imageNameFromDb, //$this->id 
        $this->destination  = ;         //its mandatory
        $this->imageNameFromDb = ;   //its mandatory
        $this->imageDelete();
    | by call this $this->imageDelete() method....
    |---------------------------------------------------------------------------------------------
    */
        public function imageDelete()
        {
            if(Storage::disk('public')->exists($this->destination.'/'.$this->imageNameFromDb))
            {
                Storage::disk('public')->delete($this->destination.'/'.$this->imageNameFromDb);
            }
            return true;
            /* 
                if(Storage::disk('public')->exists($this->destination.'/'.$this->id.".".$this->imageNameFromDb))
                {
                    Storage::disk('public')->delete($this->destination.'/'.$this->id.".".$this->imageNameFromDb);
                } 
            */
            return $this->id;
        }   
    /*
    |---------------------------------------------------------------------------------------------
    | End image delete
    |---------------------------------------------------------------------------------------------
    */





    /*
    |---------------------------------------------------------------------------------------------
    | Store Base64 image
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory
        $this->storeBase64Image();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function storeBase64Image() 
        {
            //$image_64 = $this->file;//$data['photo']; //your base64 encoded data
            $this->extension = explode('/', explode(':', substr($this->file, 0, strpos($this->file, ';')))[1])[1];   // .jpg .png .pdf
            $replace = substr($this->file, 0, strpos($this->file, ',')+1); 
            // find substring fro replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $this->file); 
            $image = str_replace(' ', '+', $image); 
            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.'.$this->extension;
            $this->resizeAndStoreImage();
            return $this->imageFileNameToStore;
            Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}", base64_decode($image));
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End base64 image insert
    |---------------------------------------------------------------------------------------------
    */




    /*
    |---------------------------------------------------------------------------------------------
    |   image upload from uploaded Image
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory only image name like  . 'image.png'
        $this->imageUploadFromUploadedImage();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function imageUploadFromUploadedImage() 
        {   
            $img = Image::make(asset('storage/products/'.$this->file))->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');

            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.jpg';
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}",$img);
            return $this->imageFileNameToStore;
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End image upload from uploaded image
    |---------------------------------------------------------------------------------------------
    */
    



    /*
    |---------------------------------------------------------------------------------------------
    |   upload image from Image link
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory
        $this->imageUploadFromImageLink();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function imageUploadFromImageLink() //CsvFile 
        {   
            $img = Image::make($this->file)->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');

            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.jpg';
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}",$img);
            return $this->imageFileNameToStore; 
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End upload image from image link
    |---------------------------------------------------------------------------------------------
    */



    /*
    |---------------------------------------------------------------------------------------------
    |   Update image from Image link
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory
        $this->imageNameFromDb = ;
        $this->imageUpdateFromImageLink();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function imageUpdateFromImageLink() //CsvFile 
        {   
            if($this->imageNameFromDb)
            {
                $this->imageDelete();
            }

            $img = Image::make($this->file)->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                    $constraint->aspectRatio();
                })->encode('jpg');

            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.jpg';
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}",$img);
            return $this->imageFileNameToStore; 
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End Update image from image link
    |---------------------------------------------------------------------------------------------
    */



    
    /*
    |---------------------------------------------------------------------------------------------
    |   default image upload.   but not working
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory only image name like  . 'image.png'
        $this->imageUploadFromImageLink();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function defaultImageUploadFromImageLink() //no-image-found 
        {   
            $img = Image::make(asset('storage/no-image-found/'.$this->file))->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('png');

            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.png';
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}",$img);
            return $this->imageFileNameToStore;
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End default image upload.
    |---------------------------------------------------------------------------------------------
    */
    


    
    /*
    |---------------------------------------------------------------------------------------------
    |   default image upload.   but not working
    | return image extension/imageName after inserting image by some property 
        $this->destination  = ;  //its mandatory 
        $this->imageWidth   = ;  //its mandatory
        $this->imageHeight  = ;  //its nullable
        $this->file         = ;  //its mandatory only image name like  . 'image.png'
        $this->imageNameFromDb = ;
        $this->defaultImageUpdateFromImageLink();
    | by call this  method....
    |---------------------------------------------------------------------------------------------
    */
        public function defaultImageUpdateFromImageLink() //no-image-found 
        {   
            if($this->imageNameFromDb)
            {
                $this->imageDelete();
            }

            $img = Image::make(asset('storage/no-image-found/'.$this->file))->resize($this->imageWidth, $this->imageHeight, function ($constraint) {
                $constraint->aspectRatio();
            })->encode('png');

            $this->imageFileNameToStore = time()."-".rand(100000,999999).'.png';
            $save = Storage::disk('public')->put("{$this->destination}/{$this->imageFileNameToStore}",$img);
            return $this->imageFileNameToStore;
        }
    /*
    |---------------------------------------------------------------------------------------------
    | End default image upload.
    |---------------------------------------------------------------------------------------------
    */
    

}
