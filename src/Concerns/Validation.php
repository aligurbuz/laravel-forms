<?php
/**
 * Created by PhpStorm.
 * User: iSerter
 * Date: 01/10/17
 * Time: 04:54
 */

namespace Iserter\LaravelForms\Concerns;


use Illuminate\Contracts\Validation\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait Validation
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @return array
     */
    public function rules()
    {
        return $this->rules;
    }

    /**
     * Validate the given request with the given rules.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  array $messages
     * @param  array $customAttributes
     * @return array
     * @internal param array $rules
     */
    public function validate(Request $request,
                             array $messages = [], array $customAttributes = [])
    {
        app(Factory::class)
            ->make($request->all(), $this->rules(), $messages, $customAttributes)
            ->validate();

        return $this->extractInputFromRules($request, $this->rules());
    }

    /**
     * Get the request input based on the given validation rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $rules
     * @return array
     */
    protected function extractInputFromRules(Request $request, array $rules)
    {
        return $request->only(collect($rules)->keys()->map(function ($rule) {
            return Str::contains($rule, '.') ? explode('.', $rule)[0] : $rule;
        })->unique()->toArray());
    }


    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param array $rules
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

}