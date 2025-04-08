<?php


namespace Pensoft\Eventsextension;

/*
// This code is in controller
$my_model = (new OrderQuestionsModel())->event(1)->active()->visible()->orderBy('order')->all();
$questionForm = new QuestionFormGenerator($my_model);
// Generate form
$form_attributes = [
    'action' => '',
    'method' => 'post',
];
$questionForm->render($form_attributes, $submit_button_text="send");
*/
class QuestionFormGenerator {

	const ANSWERS = 'answers';
	// (p - plaintext, t - text, r - radio, d - dropdown, c - checkboxes)

	const FIELD_TYPES = [
		'p' => 'p', //input one
		't' => 'text', // input one
		'e' => 'email', // input one
		'r' => 'radio', // input many (answers)
		'd' => 'select', // select many (answers)
		'c' => 'checkbox', // input many (answers)
	];
	private $fields = [];

	public function __construct($questions)
	{
		foreach($questions as $key => $question){
			$name = $question["name"];
			$_question = (string)$question['question'];
			$answers = $this->getAnswers($question);
			$type = self::FIELD_TYPES[$question['type']];
			$is_required = (bool)$question['required'];

			$required_ = '';
			if($is_required){
				$_question .= '<span class="required">*</span>';
				$required_ = 'required';
			}
			$label = "<label for=\"${name}\">${_question}</label>\n";
			$field = "";
			if($type == 'p'){
				$field = "<textarea name=\"${name}\" id=\"${name}\" rows=\"12\"></textarea>
								<script>
									// instance, using default configuration.
									CKEDITOR.replace( \"${name}\" );
									var required = parseInt(${is_required});
									if(required){
										 $(\"form\").submit( function(e) {
											var messageLength = CKEDITOR.instances[\"${name}\"].getData().replace(/(<([^>]+)>)/gi, '').length;
											var instance = CKEDITOR.instances[\"${name}\"].name;
											if( !messageLength ) {
												alert( 'Please fill out the '+ instance +' field.' );
												e.preventDefault();
											}
										});
									}

								</script>\n";
			}
			if($type == 'text' || $type == 'email'){
				$field = "<input name=\"${name}\" type=\"${type}\"  ${required_} />\n";
			}
			if($type == 'radio' || $type == 'checkbox'){
				$fields = "";
				foreach($answers as $answer){
					$value = $answer['answer'];
					$selected_ = '';
					if($answer['selected']){
						$selected_ = 'selected';
						if($type == 'checkbox'){
							$selected_ = 'checked';
						}
					}
					$fields .= "<label><input name=\"${name}[]\" type=\"${type}\" value=\"${value}\" ${required_} ${selected_}/>${value}</label>\n";
				}
				$field = $fields;
			}
			if($type == 'select'){
				$fields = "<select name=\"${name}\" ${required_} >\n";
				foreach($answers as $answer){
					$value = $answer['answer'];
					$selected_ = '';
					if($answer['selected']){
						$selected_ = 'selected';
					}
					$fields .= "<option value=\"${value}\" ${selected_}>${value}</option>\n";
				}
				$fields .= "</select>\n";
				$field = $fields;
			}

			$fieldset = "<div class=\"field\">\n${label}${field}</div>\n";
			$this->fields[] = $fieldset;
		}

	}

	public function render($options = [], $button_text = ""){
		$attributes = "";
		foreach($options as $attr => $value){
			$attributes .= " ${attr}=\"${value}\"";
		}

//		$result = "<form ${attributes}>\n";
		$result = "";
		foreach($this->fields as $fieldset){
			$result .= $fieldset;
		}
		$result .= "<div class=\"field\"><div class=\"g-recaptcha\" data-sitekey=\"6Le9quwqAAAAAEGhTG-XwovQtzrEj3waXsSHZMeP\"></div></div>\n";
		$result .= "<input type=\"submit\" name=\"tAction\" value=\"${button_text}\" class=\"btn btn-primary\">\n";
		$result .= "";
		return $result;
	}

	public function hasAnswers($question){
		return isset($question[self::ANSWERS]) && count($question[self::ANSWERS]);
	}

	public function getAnswers($question){
		if(!$this->hasAnswers($question)){
			return [];
		}
		return $question[self::ANSWERS];
	}
}
