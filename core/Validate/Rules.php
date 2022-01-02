<?php

namespace Core\Validate;

use Exception;

class Rules
{
    private array $data;
    private array $rules;
    private array $callbacks;
    private array $errors = [];
    const FIELD = 'field.';

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
        $this->callbacks = [
            'required' => new Rule(function ($subject) {
                if (empty($subject)) {
                    return false;
                }
                return $subject;
            }, 'le champ %s est requie'),
            'minL' => new Rule(function (string $field, $subject) {
                $field = $this->testField($field);
                if (strlen($subject) < (int) $field) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit avoir au moins %s caractères'),
            'maxL' => new Rule(function (string $field, $subject) {
                $field = $this->testField($field);
                if (strlen($subject) > (int) $field) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit avoir au plus %s caractères'),
            'min' => new Rule(function (string $field, $subject) {
                $field = $this->testField($field);
                if ((float) $subject < (float) $field) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit être d\'au moins %s'),
            'max' => new Rule(function (string $field, $subject) {
                $field = $this->testField($field);
                if ((float) $subject > (float) $field) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit être d\'au plus %s'),
            'confirmed' => new Rule(function (string $field, string $subject) {
                $field = $this->testField($field);
                if ($field !== $subject) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit etre confirmé'),
            'same' => new Rule(function (string $field, string $subject) {
                $field = $this->testField($field);
                if ($field !== $subject) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit être identique à %s'),
            'different' => new Rule(function (string $field, string $subject) {
                $field = $this->testField($field);
                if ($field === $subject) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit etre different du champ %s'),
            'beforeDate' => new Rule(function (string $field, string $subject) {
                if ($field == 'now') {
                    $field = "Y-m-d";
                }
                $field = $this->testField($field);
                if (date($field) < date($subject)) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit être antèrieur à la date %s'),
            'afterDate' => new Rule(function (string $field, string $subject) {
                if ($field == 'now') {
                    $field = "Y-m-d H:i:s";
                }
                $field = $this->testField($field);
                if (date($field) > date($subject)) {
                    return false;
                }
                return $subject;
            }, 'le champ %s doit etre ulterieur à la date %s')
        ];
    }

    /**
     * Vérifie si la règle existe dans
     * la liste des règle et lance le contrôle
     * des règles
     *
     * @param string $key
     * @param $subject
     * @return bool
     * @throws Exception
     */
    public function checkRule(string $key, $subject)
    {
        if (isset($this->rules[$key])) {
            $rules = array_filter(explode('|', $this->rules[$key]), function ($r) {
                return $r;
            });
            foreach ($rules as $name => $rule) {
                $ruleArgs = array_filter(explode(':', $rule), function ($r) {
                    return $r;
                });
                $function = array_shift($ruleArgs);
                $ruleArgs[] = $subject;
                if(!isset($this->callbacks[$function])){
                    throw new Exception("La régle de validation $function n'existe pas");
                }
                if (call_user_func_array($this->callbacks[$function]->callback, $ruleArgs) == false) {
                    array_pop($ruleArgs);
                    $ruleArgs = array_map(function ($ra) use ($function) {
                        if (is_int(stripos($ra, self::FIELD))) {
                            $field = str_replace(self::FIELD, '', $ra);
                            $ra = "$field:" . $this->data[str_replace(self::FIELD, '', $ra)];
                        }
                        return $ra;
                    }, $ruleArgs);
                    $this->errors[$key] = sprintf($this->callbacks[$function]->errorMessage, ...[$key, ...$ruleArgs]);
                    return false;
                }
            }
        }
        return $subject;
    }

    /**
     * Vérifie si le champ existe dans les
     * données du formulaire
     *
     * @param string $field
     * @return mixed|string
     */
    public function testField(string $field)
    {
        if (stripos($field, self::FIELD) === 0) {
            return $this->data[str_replace(self::FIELD, '', $field)];
        }
        return $field;
    }

    /**
     * Retourne les erreurs
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
