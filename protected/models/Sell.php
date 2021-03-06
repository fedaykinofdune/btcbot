<?php

/**
 * This is the model class for table "sell".
 *
 * The followings are the available columns in table 'sell':
 * @property integer $id
 * @property integer $buy_id
 * @property string $price
 * @property string $count
 * @property string $summ
 * @property string $income
 */
class Sell extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Sell the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sell';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('buy_id, price, count, summ, income', 'required'),
			array('buy_id', 'numerical', 'integerOnly'=>true),
			array('price, count, summ, income', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, buy_id, price, count, summ, income', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(				
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'buy_id' => 'Buy',
			'price' => 'Price',
			'count' => 'Count',
			'summ' => 'Summ',
			'income' => 'Income',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('buy_id',$this->buy_id);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('count',$this->count,true);
		$criteria->compare('summ',$this->summ,true);
		$criteria->compare('income',$this->income,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getTotalIncome()
	{
		$connection = Yii::app()->db;
		$sql = "
				select sum(income)
				from sell				
				";
		
		$command = $connection->createCommand($sql);
		return($command->queryScalar());
	}
	
	public static function getLast()
	{
		$buy = Sell::model()->find(array(
				'order' => 'dtm desc'
		));
		return $buy;
	}
	
	// Создание заказа
	public static function make($order)
	{	
		$buy = $order->buy;		
		$buy->sold+=$order->count;
		$buy->update(array('sold'));
	
		// Расчитываем сумму покупки, так как нужно считать для продажи доли
		$buy_summ =  $buy->summ / $buy->count * $order->count;
		
		$sell = new Sell();
		$sell->buy_id = $buy->id;
		$sell->price = $order->price;
		$sell->count = $order->count;
		$sell->summ = $order->summ - $order->fee;
		$sell->fee = $order->fee;
		$sell->income = ($order->summ-$buy_summ)-$sell->fee;
		$sell->dtm = $order->close_dtm;		
		$sell->save();
	
		return $sell;
	}
	
	
}