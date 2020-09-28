<?php

namespace GlobalCommon {

    require_once __DIR__ . '/../layers/Messaging.php';

    use Layer\DataFormatter\StringFormatter;

    // region *** Interface ***


    interface IDataValidation
    {
        public function isValid(): bool;
    }

    interface ICRUD
    {
        function create();

        function remove();

        function update();

        function delete();
    }

    interface IDataModify
    {
        public function copyValuesFrom(object $_Data);
    }

    interface IDataControl extends IDataValidation, IDataModify
    {
    }

    // endregion

    // region *** Class ***

    abstract class DataValidation implements IDataModify
    {

        final public function copyValuesFrom(object $_Data): self
        {

            foreach ($this as $key => $value) {

                if (property_exists($_Data, $key)) {
                    $this->$key = $_Data->$key;
                } else if (property_exists($_Data, strtolower($key))) {
                    $lowercaseKey = strtolower($key);
                    $this->$key   = $_Data->$lowercaseKey;
                }
            }

            return $this;
        }

    }

    class CriteriaItem extends DataValidation
    {
        public ?string $Property  = null;
        public ?string $Value     = null;
        public ?bool   $IsAND     = null;
        public ?bool   $IsLIKE    = null;
        public ?bool   $IsIN      = null;
        public ?bool   $IsNULL    = null;
        public ?bool   $IsBetween = null;
        public ?string $StartDate = null;
        public ?string $EndDate   = null;


        final public function sqlPrepare(object $_Data): self
        {
            $stringFormatter = new StringFormatter();

            foreach ($this as $key => $value) {

                if (property_exists($_Data, $key)) {
                    if ($this->$key === 'Property') {
                        $this->$key = $stringFormatter->camel2dashed($_Data->$key);
                    } else {
                        $this->$key = $_Data->$key;
                    }

                }
            }

            return $this;
        }

    }
    // endregion

}
