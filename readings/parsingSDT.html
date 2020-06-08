<html>
<head>
<link rel="stylesheet" href="dextras.css" />

<script type="text/x-mathjax-config">
    // <![CDATA[
    MathJax.Hub.Config({
        TeX: {extensions: ["AMSmath.js", "AMSsymbols.js"]},
        extensions: ["tex2jax.js"],
        jax: ["input/TeX", "output/HTML-CSS"],
        showProcessingMessages : false,
        messageStyle : "none" ,
        showMathMenu: false ,
        tex2jax: {
            processEnvironments: true,
            inlineMath: [ ['$','$'] ],
            displayMath: [ ['$$','$$'] ],
            preview : "none",
            processEscapes: true
        },
        "HTML-CSS": { linebreaks: { automatic:true, width: "latex-container"} }
    });
    // ]]>
 // MathJax.Hub.Queue(reportReload);
</script>
<script type="text/javascript" src="MathJax.js"></script>
<script type="text/javascript" src="latex2html5.min.js"></script>


</head>
<body>
<HR>
<span class=subhead>Contents</span>

<UL>
    <LI><A HREF="#ex3">Example: Counting Parentheses</A>
    <LI><A HREF="#youtry3">Test Yourself #1</A>
    <LI><A HREF="#nonLL1">Handling Non-LL(1) Grammars</A>
    <LI><A HREF="#youtry4">Test Yourself #2</A>
    <LI><A HREF="#summ">Summary</A>
</UL>

<P>
Now we consider how to implement a syntax-directed translation using
a predictive parser.
It is not obvious how to do this, since the predictive parser works by
building the parse tree top-down, while the syntax-directed translation
needs to be computed bottom-up.
Of course, we could design the parser to actually build the parse tree
(top-down), then use the translation rules to build the translation
(bottom-up).
However, that would not be very efficient.
<P>
Instead, we avoid explicitly building the parse tree by giving the parser
a second stack called the <b>semantic stack</b>:
<UL>
  <LI> The semantic stack holds nonterminals' translations; when the parse
       is finished, it will hold just one value: the translation of the
       root nonterminal (which is the translation of the whole input).
  <LI> Values are pushed onto the semantic stack (and popped off) by
       adding <b>actions</b> to the grammar rules.  The action for one
       rule must:
       <UL>
         <LI> Pop the translations of all right-hand-side nonterminals.
	 <LI> Compute and push the translation of the left-hand-side
	      nonterminal.
       </UL>
  <LI> The actions themselves are represented by <b>action numbers</b>,
       which become part of the right-hand sides of the grammar rules.
       They are pushed onto the (normal) stack along with the terminal
       and nonterminal symbols.  When an action number is the top-of-stack
       symbol, it is popped and the action is carried out.
</UL>
So what actually happens is that the action for a grammar rule 
$X \longrightarrow Y_1 \; Y_2 \; \ldots \; Y_n$
is pushed onto the (normal) stack when the derivation step
$X \longrightarrow Y_1 Y_2 \ldots Y_n$ is made,
but the action is not actually performed until complete derivations for all
of the $Y$s have been carried out.
<P>
<a name=ex3></a>
<span class=example>Counting Parentheses</span>
<P>
For example, consider the following syntax-directed translation for the
language of balanced parentheses and square brackets.
The translation of a string in the language is the number of parenthesis
pairs in the string.
</p>

<table class="cfg">
<tr>
<td align="left" style="border-bottom: 1px solid"colspan=3><b>CFG</b></td>
<td align="left" style="padding-left:40px">&nbsp</td>
<td align="left" style="border-bottom: 1px solid"><b>Translation Rules</b></td>
</tr>

<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$\varepsilon$</td>
<td></td>
<td>$Exp$.trans = 0</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>(</b> $Exp$ <b>)</b></td>
<td></td>
<td>$Exp_1$.trans = $Exp_2$.trans + 1</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>[</b> $Exp$ <b>]</b></td>
<td></td>
<td>$Exp_1$.trans = $Exp_2$.trans</td>
</tr>

</table>

<p>
The first step is to replace the translation rules with <b>translation
actions</b>.
Each action must:
<UL>
  <LI> Pop all right-hand-side nonterminals' translations from the
       semantic stack.
  <LI> Compute and push the left-hand-side nonterminal's translation.
</UL>
Here are the translation actions:
</p>

<table class="cfg">
<tr>
<td align="left" style="border-bottom: 1px solid"colspan=3><b>CFG</b></td>
<td align="left" style="padding-left:40px">&nbsp</td>
<td align="left" style="border-bottom: 1px solid"><b>Translation Actions</b></td>
</tr>

<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$\varepsilon$</td>
<td></td>
<td>push 0;</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>(</b> $Exp$ <b>)</b></td>
<td></td>
<td><tt>exp2trans</tt> = pop() ; push(<tt>exp2trans</tt> + 1)</td>
</tr>


<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>[</b> $Exp$ <b>]</b></td>
<td></td>
<td><tt>exp2trans</tt> = pop() ; push(<tt>exp2trans</tt>)</td>
</tr>


</table>
<p>
Next, each action is represented by a unique action number, and those
action numbers become part of the grammar rules:

<table class="cfg">
<tr>
<td align="left" style="border-bottom: 1px solid"colspan=3><b>CFG with Embedded Actions</b></td>
</tr>

<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$\varepsilon$ #1</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>(</b> $Exp$ <b>)</b> #2</td>
</tr>


<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs><b>[</b> $Exp$ <b>]</b> #3</td>
</tr>
</table>

<p>where</p>

<table class="cfg" style="margin-left:20px; margin-top:5px">
<tr>
<td style="padding-right:20px">#1 is </td>
<td>push 0;</td>
</tr>

<tr>
<td style="padding-right:20px">#2 is </td>
<td><tt>exp2trans</tt> = pop() ; push(<tt>exp2trans</tt> + 1)</td>
</tr>

<tr>
<td>#3 is </td>
<td><tt>exp2trans</tt> = pop() ; push(<tt>exp2trans</tt>)</td>
</tr>

</table>

<P>
Note that since action #3 just pushes exactly what is popped, that action
is redundant, and it is not necessary to have any action associated with
the third grammar rule.
Here's a picture that illustrates what happens when the input "([])" is
parsed (assuming that we have removed action #3):

<table class="cfg" style="margin-left:20px; margin-top:5px">
<tr style="border-collapse:separate">
<td style="border-bottom:1px solid"><b>Input so far</b></td>
<td style="padding-left:10px; padding-right:10px">&nbsp</td>
<td style="border-bottom:1px solid" align=center><b>Stack</b></td>
<td style="padding-left:10px; padding-right:10px">&nbsp</td>
<!--<td style="border-collapse:separate; ; padding-left:20px; border-bottom:1px solid"><b>Semantic Stack</b></td>-->
<td style="border-bottom:1px solid"><b>Semantic Stack</b></td>
<td style="padding-left:20px">&nbsp</td>
<td align=center style="border-bottom:1px solid"><b>Action</b></td>
</tr>

<tr>
<td><b>(</b></td><td></td>
<td>$Exp$ <span class=EOF></span></td><td></td>
<td>&nbsp</td><td></td>
<td>pop, push ( exp ) #2</td>
</tr>

<tr>
	<td><b>(</b></td><td></td>
	<td>
		<span class=term>(</span>
		$Exp$
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>

	<td>&nbsp</td><td></td>
	<td>pop, scan</td>
</tr>

<tr>
	<td><b>([</b></td><td></td>
	<td>
		$Exp$
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>

	<td>&nbsp</td><td></td>
	<td>pop, push "[ exp ]"</td>
</tr>

<tr>
	<td><b>([</b></td><td></td>
	<td>
		<span class=term>[</span>
		$Exp$
		<span class=term>]</span>
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>

	<td>&nbsp</td><td></td>
	<td>pop, scan</td>
</tr>

<tr>
	<td><b>([]</b></td><td></td>
	<td>
		$Exp$
		<span class=term>]</span>
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>

	<td>&nbsp</td><td></td>
	<td>pop, push $\varepsilon$ <span class=action>1</span></td>
</tr>

<tr>
	<td><b>([]</b></td><td></td>
	<td>
		<span class=action>1</span>
		<span class=term>]</span>
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>

	<td>&nbsp</td><td></td>
	<td>pop, do action <span class=action>1</span></td>
</tr>

<tr>
	<td><b>([]</b></td><td></td>
	<td>
		<span class=term>]</span>
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>
	<td align=center>0</td><td>&nbsp</td>

	<td>pop, scan</td>
</tr>

<tr>
	<td><b>([]</b></td><td></td>
	<td>
		<span class=term>)</span>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>
	<td align="center">0</td><td>&nbsp</td>

	<td>pop, scan</td>
</tr>

<tr>
	<td><b>([])</b><span class=EOF></span></td><td></td>
	<td>
		<span class=action>2</span>
		<span class=EOF></span>
	</td>
	<td></td>
	<td align="center">0</td><td></td>

	<td>pop, do action <span class=action>2</span></td>
</tr>

<tr>
	<td><b>([])</b><span class=EOF></span></td><td></td>
	<td>
		<span class=EOF></span>
	</td>
	<td></td>
	<td align="center">1</td><td>&nbsp</td>
	<td>pop, scan</td>
</tr>

<tr>
	<td><b>([])</b><span class=EOF></span></td><td></td>
	<td></td>
	<td></td>
	<td align="center"></td><td>&nbsp</td>
	<td>empty stack, accept</td>
</tr>
</table>
<p>
<span align=center style="display:block">translation of input = 1</span>
</p>

<P>
In the example above, there is no grammar rule with more than one
nonterminal on the right-hand side.
If there were, the translation action for that rule would have to
do one pop for each right-hand-side nonterminal.
For example, suppose we are using a grammar that includes the rule:
</p>
<div align=center>
$MethodBody$&nbsp$\longrightarrow$&nbsp<b>{</b>&nbsp$VarDecls$&nbsp$Stmts$&nbsp<b>}</b>
</div>

and that the
syntax-directed translation is counting the number of declarations
and statements in each method body (so the translation of $VarDecls$
is the number of derived declarations, the translation of $Stmts$
is the number of derived statements, and the translation of
$MethodBody$ is the number of derived declarations and statements).
<UL>
<pre>
CFG Rule:              methodBody -> { varDecls stmts }
Translation Rule:      methodBody.trans = varDecls.trans + stmts.trans
Translation Action:    stmtsTrans = pop(); declsTrans = pop();
	               push( stmtsTrans + declsTrans );
CFG rule with Action:  methodBody -> { varDecls stmts } #1
                       #1: stmtsTrans = pop();
		           declsTrans = pop();
			   push( stmtsTrans + declsTrans );

</pre>
</UL>
Note that the right-hand-side nonterminals' translations are popped from
the semantic stack <em>right-to-left</em>.
That is because the predictive parser does a leftmost derivation,
so the <tt>varDecls</tt> nonterminal gets "expanded" first;
i.e., its parse tree is created before the parse tree for the
<tt>stmts</tt> nonterminal.
This means that the actions that create the translation of the
<tt>varDecls</tt> nonterminal are performed first, and thus its
translation is pushed onto the semantic stack first.
<P>
Another issue that has not been illustrated yet arises when a
left-hand-side nonterminal's translation depends on the value of a
right-hand-side <em>terminal</em>.
In that case, it is important to put the action number <em>before</em>
that terminal symbol when incorporating actions into grammar rules.
This is because a terminal symbol's value is available during the parse
only when it is the "current token".
For example, if the translation of an arithmetic expression is the
value of the expression:
<UL>
<pre>
CFG Rule:              factor -> INTLITERAL
Translation Rule:      factor.trans = INTLITERAL.value
Translation Action:    push( INTLITERAL.value )
CFG rule with Action:  factor -> #1 INTLITERAL       // action BEFORE terminal
                       #1: push( currToken.value )
</pre>
</UL>
<hr>
<a name=youtry3>
<P>
<center>
<u><b>TEST YOURSELF #1</b></u>
</center></a>
<P>
For the following grammar, give (a) translation rules, (b) translation
actions with numbers, and (c) a CFG with action numbers,
so that the translation of an
input expression is the value of the expression.
Do not worry about the fact that the grammar is not LL(1).
<UL>
<pre>
exp    -> exp + term
       -> exp - term
       -> term
term   -> term * factor
       -> term / factor
       -> factor
factor -> INTLITERAL
       -> ( exp )
</pre>
</UL>
<p><a href="SOLUTIONS/LL-TRANS-ANSWERS.html#ans1">solution</a></p>
<hr>
<P>
<a name=nonLL1></a>
<H2>Handling Non-LL(1) Grammars</H2>
<P>
Recall that a non-LL(1) grammar must be transformed to an equivalent
LL(1) grammar if it is to be parsed using a predictive parser.
Recall also that the transformed grammar usually
does not reflect the underlying structure the way the original grammar did.
For example, when left recursion is removed from the grammar for
arithmetic expressions, we get grammar rules like this:
</p>
<table class="cfg" style="margin-top:5px">
<tr>
<td align="left" style="border-bottom: 1px solid"colspan=3><b>CFG</b></td>
</tr>

<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$Term$ $Exp`$</td>
</tr>

<tr>
<td class=cfg_lhs>$Exp`$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$\varepsilon$</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align="center">|</td>
<td class=cfg_rhs><b>+</b> $Term$ $Exp`$</td>
</tr>

</table>
It is not at all clear how to define
a syntax-directed translation for rules like these.
The solution is to define the syntax-directed translation
using the <em>original</em> grammar (define translation rules,
convert them to actions that push and pop using the semantic stack, and
then incorporate the action numbers into the grammar rules).
Then convert the grammar to be LL(1), <em>treating the action numbers
just like terminal grammar symbols</em>!
<P>
For example:
<UL>
<!--
<table class="cfg" style="margin-top:5px; border-bottom:1px solid; width:auto">
<tr>
<td>
<b>Non-LL(1) Grammar Rules With Actions</b>
</td>
</tr>
</table>
-->
<b>Non-LL(1) Grammar Rules With Actions</b>

<table class="cfg" style="margin-top:5px">
<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$Exp$ + $Term$ #1</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs>$Term$</td>
</tr>

<tr>
<td class=cfg_lhs>$Term$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$Term$ * $Factor$ #2</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<!--<td class=cfg_rhs>$Factor$</td>-->
<td class=cfg_rhs>$\mathit{Factor}$</td>
</tr>
</table>

<table class="cfg" style="margin-top:5px">
<tr>
<td style="padding-right:10px">#1 is </td>
<td><tt>TTrans</tt> = pop() ; <tt>ETrans</tt> = pop() ; push(<tt>ETrans</tt> + <tt>TTrans</tt>);</td>
</tr>

<tr>
<td style="padding-right:10px">#2 is </td>
<td><tt>FTrans</tt> = pop() ; <tt>TTrans</tt> = pop() ; push(<tt>TTrans</tt> * <tt>FTrans</tt>);</td>
</tr>

</table>

<b>After Removing Immediate Left Recursion</b>


<table class="cfg" style="margin-top:5px">
<tr>
<td class=cfg_lhs>$Exp$</td>
<td class=cfg_trans>$\longrightarrow$</td>
<td class=cfg_rhs>$Term$ $Exp`$</td>
</tr>

<tr>
<td class=cfg_lhs>$Exp`$</td>
<td class=cfg_trans align=center>$\longrightarrow$</td>
<td class=cfg_rhs>+ $Term$ #1 $Exp`$</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<td class=cfg_rhs>$\varepsilon$</td>
</tr>

<tr>
<td>&nbsp</td>
<td></td>
<td></td>
</tr>

<tr>
<td class=cfg_lhs>$\mathit{Term}$</td>
<td class=cfg_trans align=center>$\longrightarrow$</td>
<!--<td class=cfg_rhs>$Factor$</td>-->
<td class=cfg_rhs>$\mathit{Factor}$ $\mathit{Term}`$</td>
</tr>

<tr>
<td class=cfg_lhs>$\mathit{Term}`$</td>
<td class=cfg_trans align=center>$\longrightarrow$</td>
<!--<td class=cfg_rhs>$Factor$</td>-->
<td class=cfg_rhs>* $\mathit{Factor}$ <span class=action>2</span> $\mathit{Term}`$</td>
</tr>

<tr>
<td class=cfg_lhs></td>
<td class=cfg_trans align=center>|</td>
<!--<td class=cfg_rhs>$Factor$</td>-->
<td class=cfg_rhs>$\varepsilon$</td>
</tr>
</table>
</UL>
<hr>
<a name=youtry4>
<P>
<center>
<u><b>TEST YOURSELF #2</b></u>
</center></a>
<P>
Transform the grammar rules with actions that you wrote for the
"Test Yourself #1" exercise to LL(1) form.
Trace the actions of the predictive parser on the input <tt>2 + 3 * 4</tt>.
<P>
<p><a href="SOLUTIONS/LL-TRANS-ANSWERS.html#ans2">solution</a></p>
<hr>
<P>
<a name=summ></a>
<span class=subhead>Summary</span>
<P>
A <b>syntax-directed translation</b> is used to define the translation of a
sequence of tokens to some other value, based on a CFG for the input.
A syntax-directed translation is defined by associating a translation
rule with each grammar rule.
A translation rule defines the translation of the left-hand-side
nonterminal as a function of the right-hand-side nonterminals' translations,
and the values of the right-hand-side terminals.
To compute the translation of a string, build the parse tree, and use
the translation rules to compute the translation of each nonterminal
in the tree, bottom-up; the translation of the string is the
translation of the root nonterminal.
<P>
There is no restriction on the type of a translation;
it can be a simple type like an integer, or a complex type list an
abstract-syntax tree.
<P>
To <b>implement</b> a syntax-directed translation using a predictive parser,
the translation rules are converted to actions that manipulate the
parser's <b>semantic stack</b>.
Each action must pop all right-hand-side nonterminals' translations from
the semantic stack, then compute and push the left-hand-side nonterminal's
translation.
Next, the actions are incorporated (as action numbers) into the grammar rules.
Finally, the grammar is converted to LL(1) form (treating the action numbers
just like terminal or nonterminal symbols).
</body>
</html>
